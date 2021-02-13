<?php

namespace App\Services;

use Google\Client;
use Google_Service_Oauth2;

class GoogleOauuth2Service
{
    public function getGoogleOauth2(Client $client): Google_Service_Oauth2
    {
        return new Google_Service_Oauth2($client);
    }
}
