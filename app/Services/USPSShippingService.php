<?php

namespace App\Services;

use App\Models\PurchaseCart;
use App\Models\PurchaseItem;
use App\Models\UserAddress;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class USPSShippingService
{
    protected $clientId;
    protected $clientSecret;
    protected $customerRegistrationId;
    protected $mailerId;
    protected $baseUrl;
    protected $oauthUrl;
    protected $originZip;
    protected $mode;
    protected $addressValidatePath;

    public function __construct()
    {
        $this->clientId = config('usps.client_id');
        $this->clientSecret = config('usps.client_secret');
        $this->customerRegistrationId = config('usps.customer_registration_id');
        $this->mailerId = config('usps.mailer_id');
        $this->mode = config('usps.mode');
        $this->baseUrl = config("usps.endpoints.{$this->mode}.base");
        $this->oauthUrl = config("usps.endpoints.{$this->mode}.oauth");
        $this->originZip = config('usps.origin.zip');
        $this->addressValidatePath = config('usps.address_validate_path');
    }

    /**
     * Get OAuth access token (cached)
     */
    protected function getAccessToken(): string
    {
        $cacheKey = config('usps.token_cache_key');
        
        return Cache::remember($cacheKey, config('usps.token_cache_ttl'), function () {
            try {
                Log::info('Requesting new USPS OAuth token', [
                    'oauth_url' => $this->oauthUrl,
                    'client_id' => substr($this->clientId, 0, 10) . '...',
                    'mode' => $this->mode
                ]);
                
                // USPS OAuth v3 - client_credentials grant type
                // Documentation: https://github.com/USPS/api-examples
                $response = Http::asJson()
                    ->post($this->oauthUrl, [
                        'client_id' => $this->clientId,
                        'client_secret' => $this->clientSecret,
                        'grant_type' => 'client_credentials'
                    ]);

                Log::info('USPS OAuth response received', [
                    'status' => $response->status(),
                    'has_access_token' => isset($response->json()['access_token'])
                ]);

                if (!$response->successful()) {
                    Log::error('USPS OAuth token request failed', [
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                    throw new \Exception('Failed to get USPS OAuth token: ' . $response->body());
                }

                $data = $response->json();
                
                if (!isset($data['access_token'])) {
                    throw new \Exception('OAuth response missing access_token');
                }

                Log::info('USPS OAuth token obtained successfully');
                
                return $data['access_token'];
            } catch (\Exception $e) {
                Log::error('USPS OAuth error', ['error' => $e->getMessage()]);
                throw $e;
            }
        });
    }

    /**
     * Get shipping rates for domestic addresses (New API)
     */
    public function getDomesticRates(PurchaseCart $cart, UserAddress $address): array
    {
        try {
            // Calculate total weight
            $weightData = $this->calculateCartWeight($cart);
            
            // Determine shipping method based on cart contents
            $shippingType = $this->determineShippingType($cart);
            
            Log::info('USPS getDomesticRates', [
                'cart_id' => $cart->id,
                'weight_data' => $weightData,
                'address_id' => $address->id,
                'items_count' => $cart->items->count(),
                'shipping_type' => $shippingType
            ]);
            
            if ($weightData['pounds'] > config('usps.weight_limits.domestic')) {
                return [
                    'error' => true,
                    'message' => 'Your order exceeds USPS weight limits (' . config('usps.weight_limits.domestic') . ' lbs). Please contact info@unhushed.org to discuss shipping options.'
                ];
            }

            // Build request payload matching USPS API v3 documentation
            // https://developers.usps.com/api/74 (Domestic Prices - Base Rates)
            
            // USPS API v3 doesn't support "ALL" - we need to request specific mail classes
            // Request multiple mail classes to give customers options
            $mailClasses = $shippingType['type'] === 'media_mail' 
                ? ['MEDIA_MAIL'] 
                : ['USPS_GROUND_ADVANTAGE', 'PRIORITY_MAIL', 'PRIORITY_MAIL_EXPRESS'];
            
            $allRates = [];
            $token = $this->getAccessToken();
            
            foreach ($mailClasses as $mailClass) {
                $payload = [
                    'originZIPCode' => $this->originZip,
                    'destinationZIPCode' => $address->zip,
                    'weight' => $weightData['total_ounces'] / 16, // Convert ounces to pounds (decimal)
                    'length' => 12,
                    'width' => 10,
                    'height' => 8,
                    'mailClass' => $mailClass,
                    'processingCategory' => 'MACHINABLE',
                    'destinationEntryFacilityType' => 'NONE',
                    'rateIndicator' => 'DR',
                    'priceType' => 'RETAIL'
                ];
                
                Log::info('USPS Request Payload', [
                    'payload' => $payload,
                    'mail_class' => $mailClass
                ]);
                
                // Make API request with Bearer token
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                ])->post($this->baseUrl . '/prices/v3/base-rates/search', $payload);
                
                Log::info('USPS Response', [
                    'mail_class' => $mailClass,
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                
                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data['totalBasePrice'])) {
                        $allRates[] = [
                            'service' => $mailClass,
                            'display_name' => $this->mapServiceName($mailClass),
                            'rate' => (float)$data['totalBasePrice'],
                            'delivery_days' => $this->getDeliveryEstimate($mailClass),
                            'zone' => $data['zone'] ?? null
                        ];
                    }
                }
            }
            
            if (empty($allRates)) {
                return [
                    'error' => true,
                    'message' => 'Failed to get shipping rates from USPS. Please try again.'
                ];
            }

            // Return rates array
            return ['rates' => $allRates];
            
        } catch (\Exception $e) {
            Log::error('USPS getDomesticRates error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'error' => true,
                'message' => 'Unable to calculate shipping rates. Please try again or contact support.'
            ];
        }
    }

    /**
     * Parse new API response into standardized format
     */
    protected function parseNewApiRates(array $data, string $preferredType = 'all'): array
    {
        try {
            if (!isset($data['rates']) || empty($data['rates'])) {
                Log::warning('No rates in USPS response', ['data' => $data]);
                return [
                    'error' => true,
                    'message' => 'No shipping rates available for this address.'
                ];
            }

            $rates = [];

            foreach ($data['rates'] as $option) {
                $mailClass = $option['mailClass'] ?? 'UNKNOWN';
                $rates[] = [
                    'service' => $mailClass,
                    'display_name' => $this->mapServiceName($mailClass),
                    'rate' => (float) ($option['price'] ?? $option['totalBasePrice'] ?? 0),
                    'delivery_days' => $this->estimateDeliveryDays($mailClass),
                    'zone' => $option['zone'] ?? null,
                ];
            }

            return ['rates' => $rates];
        } catch (\Exception $e) {
            Log::error('parseNewApiRates error', ['error' => $e->getMessage()]);
            return [
                'error' => true,
                'message' => 'Unable to parse USPS rates.'
            ];
        }
    }

    /**
     * Validate address with USPS Addresses 3.0 API
     */
    public function validateAddress(UserAddress $address): array
    {
        try {
            if ($address->country !== 'US') {
                return [
                    'success' => true,
                    'validated' => false,
                    'message' => 'International address validation not available'
                ];
            }

            $token = $this->getAccessToken();
            $endpoint = rtrim($this->baseUrl, '/') . $this->addressValidatePath;

            // Prepare multiple payload variants to accommodate API changes
            $payloadVariants = [
                // Variant A: common camelCase fields
                [
                    'streetAddress' => $address->street,
                    'city' => $address->city,
                    'state' => $address->state_province,
                    'zipCode' => $address->zip,
                ],
                // Variant B: USPS standard uppercase field names
                [
                    'streetAddress' => $address->street,
                    'city' => $address->city,
                    'state' => $address->state_province,
                    'ZIPCode' => $address->zip,
                ],
                // Variant C: nested under "address"
                [
                    'address' => [
                        'streetAddress' => $address->street,
                        'city' => $address->city,
                        'state' => $address->state_province,
                        'ZIPCode' => $address->zip,
                    ]
                ],
            ];

            $lastResponse = null;
            $attempts = [];

            // First try GET request with query parameters (USPS v3 uses GET)
            $queryParams = [
                'streetAddress' => $address->street,
                'city' => $address->city,
                'state' => $address->state_province,
                'ZIPCode' => $address->zip,
            ];

            Log::info('USPS Address Validation Attempt (GET)', [
                'endpoint' => $endpoint,
                'params' => $queryParams,
                'mode' => $this->mode,
            ]);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->get($endpoint, $queryParams);

            if ($response->successful()) {
                Log::info('USPS Address Validation Success (GET)', [
                    'status' => $response->status(),
                    'response' => $response->json(),
                ]);
                $data = $response->json();
                return [
                    'success' => true,
                    'validated' => true,
                    'address' => $data['address'] ?? $data['validatedAddress'] ?? $data['result'] ?? $data,
                ];
            }

            Log::warning('USPS GET validation failed, trying POST variants', [
                'status' => $response->status(),
                'body' => substr($response->body(), 0, 300),
            ]);

            $lastResponse = $response;

            // If GET didn't work, try POST with different payload variants
            foreach ($payloadVariants as $idx => $payload) {
                Log::info('USPS Address Validation Attempt (POST)', [
                    'attempt' => $idx + 1,
                    'endpoint' => $endpoint,
                    'payload' => $payload,
                    'mode' => $this->mode,
                ]);

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                ])->post($endpoint, $payload);

                $lastResponse = $response;
                $attempts[] = [
                    'attempt' => $idx + 1,
                    'status' => $response->status(),
                    'body_preview' => substr($response->body(), 0, 200),
                ];

                if ($response->successful()) {
                    Log::info('USPS Address Validation Success', [
                        'attempt' => $idx + 1,
                        'status' => $response->status(),
                        'response' => $response->json(),
                    ]);
                    $data = $response->json();
                    return [
                        'success' => true,
                        'validated' => true,
                        'address' => $data['address'] ?? $data['validatedAddress'] ?? $data['result'] ?? $data,
                    ];
                }

                // If 405/404, keep trying other payload shapes
                if (in_array($response->status(), [404, 405])) {
                    Log::warning('USPS Address Validation endpoint issue', [
                        'attempt' => $idx + 1,
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]);
                    continue;
                }

                // If 400, try next payload variant
                if ($response->status() === 400) {
                    Log::info('USPS Address Validation 400 - trying next variant', [
                        'attempt' => $idx + 1,
                        'body' => $response->body(),
                    ]);
                    continue;
                }

                // Other errors - break after first non-404/405/400
                Log::warning('USPS Address Validation non-recoverable error', [
                    'attempt' => $idx + 1,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                break;
            }

            // If we reach here, all attempts failed
            $errorData = $lastResponse ? ($lastResponse->json() ?? []) : [];
            $errorMessage = $errorData['message'] ?? $errorData['detail'] ?? ($errorData['error']['message'] ?? 'Address validation failed');
            $fieldErrors = [];
            if (isset($errorData['errors']) && is_array($errorData['errors'])) {
                foreach ($errorData['errors'] as $error) {
                    if (isset($error['field'])) {
                        $fieldErrors[$error['field']] = $error['message'] ?? 'Invalid field';
                    }
                }
            }

            Log::warning('USPS address validation failed (all variants)', [
                'attempts' => count($attempts),
                'final_status' => $lastResponse ? $lastResponse->status() : null,
                'final_body' => $lastResponse ? $lastResponse->body() : null,
            ]);

            return [
                'success' => false,
                'validated' => false,
                'message' => $errorMessage,
                'field_errors' => $fieldErrors
            ];
        } catch (\Exception $e) {
            Log::error('USPS validateAddress exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return [
                'success' => false,
                'validated' => false,
                'message' => 'Address validation unavailable',
                'details' => $e->getMessage()
            ];
        }
    }
    /**
     * Test USPS OAuth token - verify it's working
     */
    public function testOAuthToken(): array
    {
        try {
            $token = $this->getAccessToken();
            
            Log::info('USPS OAuth Token Test', [
                'token_obtained' => !empty($token),
                'token_prefix' => substr($token, 0, 20) . '...',
                'token_length' => strlen($token),
            ]);
            
            return [
                'success' => true,
                'token_obtained' => true,
                'message' => 'OAuth token obtained successfully'
            ];
        } catch (\Exception $e) {
            Log::error('USPS OAuth Token Test Failed', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'token_obtained' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Test USPS API connectivity by probing available endpoints
     */
    public function testApiConnectivity(): array
    {
        try {
            $token = $this->getAccessToken();
            
            $endpointsToTest = [
                'Domestic Prices' => '/domestic-prices/v3/base-rates/search',
                'Address Validation' => '/addresses/v3/address-validate',
                'Address Info' => '/addresses/v3/address',
            ];
            
            $results = [];
            
            foreach ($endpointsToTest as $name => $path) {
                $endpoint = $this->baseUrl . $path;
                
                try {
                    // Just test HEAD or OPTIONS
                    $response = Http::withHeaders([
                        'Authorization' => 'Bearer ' . $token,
                        'Content-Type' => 'application/json',
                    ])->get($endpoint);
                    
                    $results[$name] = [
                        'status' => $response->status(),
                        'accessible' => $response->status() < 500,
                    ];
                } catch (\Exception $e) {
                    $results[$name] = [
                        'status' => 'error',
                        'error' => $e->getMessage(),
                    ];
                }
            }
            
            Log::info('USPS API Connectivity Test Results', $results);
            
            return [
                'success' => true,
                'results' => $results
            ];
        } catch (\Exception $e) {
            Log::error('USPS API Connectivity Test Failed', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Map USPS API service names to user-friendly names
     */
    protected function mapServiceName(string $mailClass): string
    {
        $serviceMap = [
            'PRIORITY_MAIL' => 'USPS Priority Mail',
            'PRIORITY_MAIL_EXPRESS' => 'USPS Priority Mail Express',
            'FIRST_CLASS' => 'USPS First-Class Mail',
            'FIRST_CLASS_PACKAGE_SERVICE' => 'USPS First-Class Package',
            'PARCEL_SELECT' => 'USPS Parcel Select Ground',
            'MEDIA_MAIL' => 'USPS Media Mail',
            'LIBRARY_MAIL' => 'USPS Library Mail',
            'USPS_GROUND_ADVANTAGE' => 'USPS Ground Advantage',
        ];

        return $serviceMap[$mailClass] ?? ucwords(str_replace('_', ' ', strtolower($mailClass)));
    }

    /**
     * Get shipping rates for international addresses (New API)
     */
    public function getInternationalRates(PurchaseCart $cart, UserAddress $address): array
    {
        try {
            $weightData = $this->calculateCartWeight($cart);
            
            if ($weightData['pounds'] > config('usps.weight_limits.international')) {
                return [
                    'error' => true,
                    'message' => 'Your order exceeds USPS international weight limits (' . config('usps.weight_limits.international') . ' lbs). Please contact info@unhushed.org to discuss shipping options.'
                ];
            }

            // Build request payload for international rates
            $payload = [
                'originZIPCode' => $this->originZip,
                'foreignPostalCode' => $address->zip ?? '',
                'destinationCountryCode' => $address->country,
                'weight' => $weightData['pounds'],
                'length' => 12,
                'width' => 10,
                'height' => 8,
                'mailClass' => 'ALL',
                'rateIndicator' => 'I', // International rates
                'priceType' => 'RETAIL',
            ];
            
            $token = $this->getAccessToken();
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/international-prices/v3/base-rates/search', $payload);
            
            if (!$response->successful()) {
                return [
                    'error' => true,
                    'message' => 'Failed to get international shipping rates. Please contact support.'
                ];
            }

            return $this->parseNewApiRates($response->json());
            
        } catch (\Exception $e) {
            Log::error('USPS getInternationalRates error', ['error' => $e->getMessage()]);
            return [
                'error' => true,
                'message' => 'Unable to calculate international shipping rates.'
            ];
        }
    }

    

    /**
     * Calculate total weight from cart items
     */
    protected function calculateCartWeight(PurchaseCart $cart): array
    {
        $totalOunces = 0;

        foreach ($cart->items as $item) {
            // Skip digital items (ship_type = 0)
            if ($item->variant && (int)$item->variant->ship_type !== 0) {
                // Use separate lbs/oz fields for accurate weight calculation
                $itemWeightLbs = $item->variant->weight_lbs ?? 0;
                $itemWeightOz = $item->variant->weight_oz ?? 0;
                $itemWeightInOunces = ($itemWeightLbs * 16) + $itemWeightOz;
                $totalOunces += ($itemWeightInOunces * $item->quantity);
            }
        }

        $pounds = floor($totalOunces / 16);
        $ounces = $totalOunces % 16;

        return [
            'pounds' => $pounds,
            'ounces' => $ounces,
            'total_ounces' => $totalOunces
        ];
    }

    /**
     * Check if cart contains only digital items
     * ship_type: 0=digital, 1=standard, 2=media
     */
    public function isDigitalOnly(PurchaseCart $cart): bool
    {
        foreach ($cart->items as $item) {
            // If any item is physical (ship_type 1 or 2), not digital-only
            if ($item->variant && (int)$item->variant->ship_type !== 0) {
                return false;
            }
        }
        return true;
    }

    /**
     * Determine appropriate shipping method based on cart contents
     * ship_type: 0=digital, 1=physical/standard, 2=book/media mail
     */
    protected function determineShippingType(PurchaseCart $cart): array
    {
        $hasMediaMail = false;
        $hasStandard = false;

        foreach ($cart->items as $item) {
            if ($item->variant) {
                $shipType = (int)$item->variant->ship_type;
                
                if ($shipType === 2) {
                    $hasMediaMail = true;
                } elseif ($shipType === 1) {
                    $hasStandard = true;
                }
            }
        }

        // If cart contains ONLY Media Mail eligible items (books), use Media Mail
        if ($hasMediaMail && !$hasStandard) {
            return [
                'mailClass' => 'MEDIA_MAIL',
                'type' => 'media_mail'
            ];
        }

        // For mixed carts or standard items, use Parcel Select
        return [
            'mailClass' => 'PARCEL_SELECT',
            'type' => 'standard'
        ];
    }
}
