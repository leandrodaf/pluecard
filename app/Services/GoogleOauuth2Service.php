<?php

namespace App\Services;

use Google\Client;
use Google_Service_Oauth2;

class GoogleOauuth2Service
{
    /**
     * Create new google client OAUTH2 instance.
     * @param Client $client
     * @return Google_Service_Oauth2
     */
    public function getGoogleOauth2(Client $client): Google_Service_Oauth2
    {
        return new Google_Service_Oauth2($client);
    }
}
