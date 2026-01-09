<?php

namespace App\Services;

use App\Models\User;
use App\Models\Organizations;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

/**
 * ActiveCampaignService
 * 
 * Centralized service for all Active Campaign CRM integrations
 * Handles contact sync, field updates, tags, and list management
 * 
 * Features:
 * - Automatic contact sync on user create/update/delete
 * - Field value management (user_id, org_id, last_login, etc)
 * - Tag management for automations
 * - List subscriptions (newsletter, site users, etc)
 * - Error handling and logging
 */
class ActiveCampaignService
{
    protected $ac;
    protected $baseUrl;
    protected $apiKey;
    protected $fields;
    protected $lists;

    /**
     * AC Field IDs
     */
    const FIELD_USER_ID = 1;           // Internal user_id
    const FIELD_ORG_ID = 2;            // Organization ID
    const FIELD_AGE_WORK_WITH = 3;     // Age group worked with
    const FIELD_ADDRESS = 4;           // Address
    const FIELD_BIRTHDAY = 5;          // Birthday
    const FIELD_LAST_LOGIN = 10;       // Last login date
    const FIELD_USER_COUNT_ES = 16;    // Elementary school user count
    const FIELD_USER_COUNT_MS = 17;    // Middle school user count
    const FIELD_USER_COUNT_HS = 18;    // High school user count
    const FIELD_MESSAGE = 19;          // Custom message
    const FIELD_LAST_MEETING = 21;     // Last meeting date
    const FIELD_CHILDREN = 22;         // Children
    const FIELD_BOT_FILTER = 23;       // Bot filter
    const FIELD_FB_FUNDRAISER_YEAR = 24;   // Fundraiser year
    const FIELD_FB_FUNDRAISER_TOTAL = 25;  // Fundraiser total
    const FIELD_TRACKING = 26;         // Tracking info (shipping)

    /**
     * AC List IDs
     */
    const LIST_NEWSLETTER = 1;         // Newsletter subscribers
    const LIST_SITE_USER = 2;          // Site users

    /**
     * AC Tag IDs
     */
    const TAG_ABANDONED_CART = 140;    // Abandoned cart automation
    const TAG_CART_SHIPPED = 229;      // Cart shipped automation

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->baseUrl = config('services.activecampaign.url');
        $this->apiKey = config('services.activecampaign.key');
        
        if (!$this->baseUrl || !$this->apiKey) {
            Log::warning('ActiveCampaignService: Missing configuration (url or key)');
        }

        // Field mappings
        $this->fields = [
            'user_id' => self::FIELD_USER_ID,
            'org_id' => self::FIELD_ORG_ID,
            'age_work_with' => self::FIELD_AGE_WORK_WITH,
            'address' => self::FIELD_ADDRESS,
            'birthday' => self::FIELD_BIRTHDAY,
            'last_login' => self::FIELD_LAST_LOGIN,
            'user_count_es' => self::FIELD_USER_COUNT_ES,
            'user_count_ms' => self::FIELD_USER_COUNT_MS,
            'user_count_hs' => self::FIELD_USER_COUNT_HS,
            'message' => self::FIELD_MESSAGE,
            'last_meeting' => self::FIELD_LAST_MEETING,
            'children' => self::FIELD_CHILDREN,
            'bot_filter' => self::FIELD_BOT_FILTER,
            'fb_year' => self::FIELD_FB_FUNDRAISER_YEAR,
            'fb_total' => self::FIELD_FB_FUNDRAISER_TOTAL,
            'tracking' => self::FIELD_TRACKING,
        ];

        // List mappings
        $this->lists = [
            'newsletter' => self::LIST_NEWSLETTER,
            'site_user' => self::LIST_SITE_USER,
        ];
    }

    /**
     * Sync user to Active Campaign on creation
     * 
     * @param User $user
     * @param array $options - ['lists' => ['newsletter', 'site_user'], 'tags' => [...]]
     * @return array|bool
     */
    public function syncUserOnCreate(User $user, array $options = [])
    {
        try {
            $contact = [
                'email' => $user->email,
                'first_name' => $this->getFirstName($user->name),
                'last_name' => $this->getLastName($user->name),
                'field[' . self::FIELD_USER_ID . ',0]' => $user->id,
            ];

            // Add organization if exists
            if ($user->org_id) {
                $contact['field[' . self::FIELD_ORG_ID . ',0]'] = $user->org_id;
            }

            // Add lists
            $lists = $options['lists'] ?? [];
            foreach ($lists as $listKey) {
                $listId = $this->lists[$listKey] ?? null;
                if ($listId) {
                    $contact["p[{$listId}]"] = $listId;
                    $contact["status[{$listId}]"] = 1; // Active
                }
            }

            // Add tags
            $tags = $options['tags'] ?? [];
            if (!empty($tags)) {
                $contact['tags'] = implode(',', $tags);
            }

            // Default tags
            $contact['tags'] = isset($contact['tags']) ? $contact['tags'] . ',1_ENGLISH' : '1_ENGLISH';

            return $this->syncContact($contact);
        } catch (\Exception $e) {
            Log::error('AC sync on user create failed', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Sync user updates to Active Campaign
     * 
     * @param User $user
     * @param array $fieldUpdates - ['field_key' => value, ...]
     * @return array|bool
     */
    public function syncUserUpdate(User $user, array $fieldUpdates = [])
    {
        try {
            // Always include basic info
            $contact = [
                'email' => $user->email,
                'first_name' => $this->getFirstName($user->name),
                'last_name' => $this->getLastName($user->name),
            ];

            // Add field updates
            foreach ($fieldUpdates as $fieldKey => $value) {
                $fieldId = $this->fields[$fieldKey] ?? null;
                if ($fieldId && $value !== null) {
                    $contact['field[' . $fieldId . ',0]'] = $value;
                }
            }

            return $this->syncContact($contact);
        } catch (\Exception $e) {
            Log::error('AC sync on user update failed', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Update last login for user
     * 
     * @param User $user
     * @param string $date - Date string (Y-m-d format)
     * @return array|bool
     */
    public function updateLastLogin(User $user, $date = null)
    {
        $date = $date ?? now()->toDateString();
        return $this->syncUserUpdate($user, ['last_login' => $date]);
    }

    /**
     * Add tag to contact by email
     * 
     * @param string $email
     * @param int $tagId
     * @return array|bool
     */
    public function addTagByEmail($email, $tagId)
    {
        try {
            // Find contact by email
            $contact = $this->getContactByEmail($email);
            if (!$contact) {
                Log::warning('AC: Contact not found for tag', ['email' => $email, 'tag_id' => $tagId]);
                return false;
            }

            $contactId = $contact['id'];
            $payload = [
                'contactTag' => [
                    'contact' => $contactId,
                    'tag' => $tagId,
                ]
            ];

            return $this->callApi('/api/3/contactTags/', 'POST', $payload);
        } catch (\Exception $e) {
            Log::error('AC add tag failed', [
                'email' => $email,
                'tag_id' => $tagId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Add contact to list by email
     * 
     * @param string $email
     * @param int $listId
     * @return array|bool
     */
    public function addToListByEmail($email, $listId)
    {
        try {
            $contact = [
                'email' => $email,
                "p[{$listId}]" => $listId,
                "status[{$listId}]" => 1, // Active
            ];

            return $this->syncContact($contact);
        } catch (\Exception $e) {
            Log::error('AC add to list failed', [
                'email' => $email,
                'list_id' => $listId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Update tracking info (shipping number) for contact
     * 
     * @param string $email
     * @param string $tracking - Tracking number
     * @return array|bool
     */
    public function updateTracking($email, $tracking)
    {
        try {
            $contact = $this->getContactByEmail($email);
            if (!$contact) {
                Log::warning('AC: Contact not found for tracking update', ['email' => $email]);
                return false;
            }

            $payload = [
                'contact' => [
                    'id' => $contact['id'],
                    'fieldValues' => [
                        [
                            'field' => self::FIELD_TRACKING,
                            'value' => $tracking,
                        ]
                    ]
                ]
            ];

            return $this->callApi('/api/3/contacts/' . $contact['id'], 'PUT', $payload);
        } catch (\Exception $e) {
            Log::error('AC update tracking failed', [
                'email' => $email,
                'tracking' => $tracking,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Sync contact via contact/sync endpoint
     * Used for upsert (update or create)
     * 
     * @param array $contactData
     * @return array|bool
     */
    public function syncContact($contactData)
    {
        try {
            return $this->callApi('/api/3/contact/sync', 'POST', $contactData);
        } catch (\Exception $e) {
            Log::error('AC contact sync failed', [
                'email' => $contactData['email'] ?? 'unknown',
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Get contact by email
     * 
     * @param string $email
     * @return array|null
     */
    public function getContactByEmail($email)
    {
        try {
            $result = $this->callApi('/api/3/contacts?email=' . urlencode($email));
            if ($result && isset($result['contacts']) && !empty($result['contacts'])) {
                return $result['contacts'][0];
            }
            return null;
        } catch (\Exception $e) {
            Log::error('AC get contact failed', [
                'email' => $email,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Make API call to Active Campaign
     * 
     * @param string $endpoint - API endpoint (e.g., /api/3/contacts)
     * @param string $method - HTTP method (GET, POST, PUT, DELETE)
     * @param array $payload - Data to send
     * @return array|null
     */
    protected function callApi($endpoint, $method = 'GET', $payload = [])
    {
        if (!$this->baseUrl || !$this->apiKey) {
            Log::error('AC API call failed: Missing configuration');
            return null;
        }

        try {
            $url = rtrim($this->baseUrl, '/') . $endpoint;
            
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_HTTPHEADER => [
                    'Accept: application/json',
                    'Content-Type: application/json',
                    'Api-Token: ' . $this->apiKey,
                ],
            ]);

            if (!empty($payload)) {
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));
            }

            $response = curl_exec($curl);
            $curlError = curl_error($curl);
            curl_close($curl);

            if ($curlError) {
                Log::error('AC curl error', [
                    'endpoint' => $endpoint,
                    'method' => $method,
                    'error' => $curlError
                ]);
                return null;
            }

            $result = json_decode($response, true);
            
            // Log API errors
            if ($result && isset($result['errors'])) {
                Log::warning('AC API error', [
                    'endpoint' => $endpoint,
                    'errors' => $result['errors']
                ]);
            }

            return $result;
        } catch (\Exception $e) {
            Log::error('AC API call exception', [
                'endpoint' => $endpoint,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Helper: Get first name from full name
     */
    protected function getFirstName($fullName)
    {
        $parts = explode(' ', trim($fullName));
        return array_shift($parts) ?? '';
    }

    /**
     * Helper: Get last name from full name
     */
    protected function getLastName($fullName)
    {
        $parts = explode(' ', trim($fullName));
        array_shift($parts); // Remove first name
        return implode(' ', $parts) ?: '';
    }

    /**
     * Get or create tag by name
     * 
     * @param string $tagName
     * @return int|null Tag ID
     */
    public function getOrCreateTag($tagName)
    {
        try {
            // Search for existing tag
            $response = $this->callApi('/api/3/tags?search=' . urlencode($tagName), 'GET');
            
            if (!empty($response['tags'])) {
                foreach ($response['tags'] as $tag) {
                    if (strcasecmp($tag['tag'], $tagName) === 0) {
                        return (int)$tag['id'];
                    }
                }
            }
            
            // Create new tag if not found
            $payload = [
                'tag' => [
                    'tag' => $tagName,
                    'tagType' => 'contact',
                    'description' => 'Auto-created for donation tracking'
                ]
            ];
            
            $response = $this->callApi('/api/3/tags', 'POST', $payload);
            
            if (!empty($response['tag']['id'])) {
                return (int)$response['tag']['id'];
            }
            
            return null;
        } catch (\Exception $e) {
            Log::error('AC: Failed to get/create tag', [
                'tag_name' => $tagName,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Add tag to contact by name
     * 
     * @param string $email
     * @param string $tagName
     * @return array|bool
     */
    public function addTagByName($email, $tagName)
    {
        try {
            $tagId = $this->getOrCreateTag($tagName);
            if (!$tagId) {
                Log::warning('AC: Could not get/create tag', ['email' => $email, 'tag_name' => $tagName]);
                return false;
            }
            
            return $this->addTagByEmail($email, $tagId);
        } catch (\Exception $e) {
            Log::error('AC: Failed to add tag by name', [
                'email' => $email,
                'tag_name' => $tagName,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Tag user as donor with current year
     * 
     * @param User $user
     * @return bool
     */
    public function tagDonor(User $user)
    {
        try {
            $currentYear = date('Y');
            
            // Add "Donor" tag
            $this->addTagByName($user->email, 'Donor');
            
            // Add "Donor: YYYY" tag
            $this->addTagByName($user->email, "Donor: {$currentYear}");
            
            Log::info('AC: Tagged user as donor', [
                'user_id' => $user->id,
                'email' => $user->email,
                'year' => $currentYear
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('AC: Failed to tag donor', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}

