<?php
/* 
 * @copyright (C) 2020 Michiel Keijts, Normit
 * 
 * @Licence MIT
 */

namespace TaboolaApi\Client;

use TaboolaApi\Client\Authentication\AuthenticationInterface;
use TaboolaApi\Exceptions\InvalidCredentialsException;
use GuzzleHttp\Client;

class ClientCredentialsAuthentication implements AuthenticationInterface {
    
    private $url = 'https://backstage.taboola.com/backstage/oauth/token';
    
    public function Authenticate() : string
    {
        $client = new Client();
        
        $response = $client->post($this->url, [
            'headers'=> [
                'content-type'=> 'application/json'
            ],
            'body' => [
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
            return $jsonBody['access_token'];
        }
        
        throw new InvalidResponseBody("Expected a JSON body: ". json_last_error_msg());
        return "";
    }
}