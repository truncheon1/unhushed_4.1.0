<?php
namespace App\Http\Controllers\Finance;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Data\IPPCustomer;
use QuickBooksOnline\API\Facades\Customer;

class QuickBooksController extends Controller 
{
    protected $dataService;
    
    // Initialize QuickBooks connection only when needed
    protected function initializeQuickBooks() {
        if ($this->dataService) {
            return $this->dataService;
        }
        
        session_start();
        
        $this->dataService = DataService::Configure(array(
            'auth_mode'     => 'oauth2',
            'client_id'     => 'ABdpUvw2dMTsbTUweANsBT6ISVXhVrWvA1AArBAE82OXE9MlUC',
            'client_secret' => \Config::get('sandbox_secret'),
            'redirect_uri'  => \Config::get('sandbox_redirect'),
            'scope'         => \Config::get('scope'),
            'base_url'      => "development"
        ));
        
        $OAuth2LoginHelper = $this->dataService->getOAuth2LoginHelper();
        $authUrl = $OAuth2LoginHelper->getAuthorizationCodeURL();
        
        // Store the url in PHP Session Object;
        $_SESSION['authUrl'] = $authUrl;
        
        //set the access token using the auth object
        if (isset($_SESSION['sessionAccessToken'])) {
            $accessToken = $_SESSION['sessionAccessToken'];
            $accessTokenJson = array('token_type' => 'bearer',
                'access_token' => $accessToken->getAccessToken(),
                'refresh_token' => $accessToken->getRefreshToken(),
                'x_refresh_token_expires_in' => $accessToken->getRefreshTokenExpiresAt(),
                'expires_in' => $accessToken->getAccessTokenExpiresAt()
            );
            $this->dataService->updateOAuth2Token($accessToken);
            $oauthLoginHelper = $this->dataService->getOAuth2LoginHelper();
            $CompanyInfo = $this->dataService->getCompanyInfo();
        }
        
        return $this->dataService;
    }

    public function quickbooks(Request $request, $path = 'educators'){
        return view('backend.finance.quickbooks.index')->with('path', get_path($path));
    }

    public function add_customer(Request $request, $path = 'educators'){
        return view('backend.finance.quickbooks.add_customer')->with('path', get_path($path));
    }
}