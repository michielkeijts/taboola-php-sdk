<?php
/* 
 * @copyright (C) 2020 Michiel Keijts, Normit
 * 
 * 
 * @licence MIT
 */

namespace TaboolaApi\Client\Requests;

use TaboolaApi\Client\Requests\BaseRequest;

class TaboolaAccountRequest extends BaseRequest {
    
    /**
     * The Endpoint URL
     * @var string 
     */
    protected $endpoint = "users/current/account";
    
    public function getAccountDetails() 
    {
        return $this->request();
    }
}