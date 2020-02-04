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

class PasswordAuthentication implements AuthenticationInterface {
    
    private $url = 'https://backstage.taboola.com/backstage/oauth/token';
    
    public function Authenticate() : string
    {
        throw new Exception("Not Implemented");
        return "";        
    }
}