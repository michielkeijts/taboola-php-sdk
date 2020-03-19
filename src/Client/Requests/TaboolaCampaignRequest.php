<?php
/* 
 * @copyright (C) 2020 Michiel Keijts, Normit
 * 
 * 
 * @licence MIT
 */

namespace TaboolaApi\Client\Requests;

use TaboolaApi\Client\Requests\BaseRequest;

class TaboolaCampaignRequest extends BaseRequest {
    /**
     * The Endpoint URL
     * @var string 
     */
    protected $endpoint = "%s/campaigns/";
    
    public function getAllCampaigns(string $account_id) 
    {
        return $this->request('get',"", sprintf($this->endpoint, $account_id));
    }
}