<?php
/* 
 * @copyright (C) 2020 Michiel Keijts, Normit
 * 
 * @Licence MIT
 */

namespace TaboolaApi\Client\Authentication;

use TaboolaApi\Client\Authentication\BaseAuthentication;
use TaboolaApi\Exceptions\InvalidCredentialsException;
use TaboolaApi\Exceptions\InvalidResponseBody;
use GuzzleHttp\Client;

class ClientCredentialsAuthentication extends BaseAuthentication {
    
    private $url = 'https://backstage.taboola.com/backstage/oauth/token';
    
    public function Authenticate() : string
    {
        $client = new Client();
        
        $response = $client->post($this->url, [
            'headers'=> [

            ],
            'form_params' => [
                'client_id' => $this->client_id,
                'client_secret' => $this->client_secret,
                'grant_type' => "client_credentials"
            ]
        ]);
        
        if ($response->getStatusCode() !== 200) {
            throw new InvalidCredentialsException("No valid status returned. Check credentials");
        }
        
        $stringBody = $response->getBody();
        $jsonBody = json_decode($stringBody);
        
        if (json_last_error() === JSON_ERROR_NONE) {
            return $jsonBody->access_token;
        }
        
        throw new InvalidResponseBody("Expected a JSON body: ". json_last_error_msg());
        return "";
    }
}