<?php

use App\Services\GoogleOauuth2Service;
use Google\Client as Googleclient;

class GoogleOauuth2ServiceTest extends TestCase
{
    public function testMustReturnOauthGoogleInstance()
    {
        $client = Mockery::mock(Googleclient::class);

        $oAuth2 = $this->app->make(GoogleOauuth2Service::class)->getGoogleOauth2($client);

        $this->assertInstanceOf('Google_Service_Oauth2', $oAuth2);
    }
}
