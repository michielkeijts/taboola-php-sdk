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
    
    /**
     * Get the Advertiser accounts, which are account
     * @param string $account
     * @return type
     */
    public function getAdvertisers(string $account_id) 
    {
        return $this->request("get", "", $account_id . '/advertisers');
    }
}