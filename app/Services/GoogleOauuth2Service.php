<?php

namespace App\Services;

use Google_Client as GoogleClient;
use Google_Service_Oauth2;

class GoogleOauuth2Service
{
    /**
     * Create new google client OAUTH2 instance.
     * @param GoogleClient $client
     * @return Google_Service_Oauth2
     */
    public function getGoogleOauth2(GoogleClient $client): Google_Service_Oauth2
    {
        return new Google_Service_Oauth2($client);
    }
}
