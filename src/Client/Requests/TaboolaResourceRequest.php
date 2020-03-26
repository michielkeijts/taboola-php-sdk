<?php
/* 
 * @copyright (C) 2020 Michiel Keijts, Normit
 * 
 * 
 * @licence MIT
 */

namespace TaboolaApi\Client\Requests;

use TaboolaApi\Client\Requests\BaseRequest;

class TaboolaResourceRequest extends BaseRequest {
    
    /**
     * The Endpoint URL
     * @var string 
     */
    protected $endpoint = "resources";
    
    /**
     * Get a list of available countries
     * @return response
     */
    public function getCountries() 
    {
        return $this->request("get","",$this->endpoint. '/countries');
    }
}