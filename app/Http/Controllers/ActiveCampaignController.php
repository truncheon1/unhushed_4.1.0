<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ActiveCampaign;

class ActiveCampaignController extends Controller
{

    private $ac;

    public function __construct() {
        //instantiate API
        $this->ac = new \ActiveCampaign(\Config::get('services.activecampaign.url'), \Config::get('services.activecampaign.key'));
        if (!(int)$this->ac->credentials_test()) {
		echo "<p>Access denied: Invalid credentials (URL and/or API key).</p>";
		die();
	    }
    }

    /*
    Custom Fields
        "id": "19", "title": "Message", "type": "textarea",
        "id": "11", "title": "Job Title-delete", "type": "text",
        "id": "2",  "title": "Organization", "type": "text",
        "id": "10", "title": "Last Login", "type": "date",
        "id": "1",  "title": "User ID", "type": "text"
        "id": "5",  "title": "Birthday", "type": "date"
        "id": "21", "title": "Last Meeting", "type": "date", 

    */
}
