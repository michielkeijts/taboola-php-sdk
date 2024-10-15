<?php
/*
 * @copyright (C) 2020 Michiel Keijts, Normit
 *
 * @Licence MIT
 */

namespace TaboolaApi\Client\Authentication;

use TaboolaApi\Client\Authentication\BaseAuthentication;
use TaboolaApi\Exceptions\InvalidCredentialsException;
use GuzzleHttp\Client;
use Exception;

class PasswordAuthentication extends BaseAuthentication {

    private $url = 'https://backstage.taboola.com/backstage/oauth/token';

    public function Authenticate() : string
    {
        throw new Exception("Not Implemented");
        return "";
    }
}
