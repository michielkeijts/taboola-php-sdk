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
    
    public function getCampaigns(string $account_id) 
    {
        return $this->request('get',"", sprintf($this->endpoint, $account_id));
    }
    
    public function getCampaign(string $account_id, int $id) 
    {
        return $this->request('get',"", sprintf($this->endpoint. '%d', $account_id, $id));
    }
    
    public function createCampaign(string $account_id, array $data) 
    {
        return $this->request('post', $data, sprintf($this->endpoint, $account_id));
    }
    
    public function updateCampaign(string $account_id, string $id, array $data) 
    {
        return $this->request('post', $data, sprintf($this->endpoint . DS . '%s', $account_id, $id));
    }
    
    public function deleteCampaign(string $account_id, string $campaign_id)
    {
        return $this->request('delete', "", sprintf($this->endpoint . DS . '%s', $account_id, $campaign_id));
    }
    
    public function getCampaignItems(string $account_id, string $campaign_id)
    {
        return $this->request('get', "", sprintf($this->endpoint . DS . '%s/items', $account_id, $campaign_id));
    }
    
    public function getCampaignItem(string $account_id, string $campaign_id, string $campaign_item_id)
    {
        return $this->request('get', "", sprintf($this->endpoint . DS . '%s/items/%s', $account_id, $campaign_id, $campaign_item_id));
    }
    
    public function createCampaignItem(string $account_id, string $campaign_id, array $data)
    {
        return $this->request('post', $data, sprintf($this->endpoint . DS . '%s/items', $account_id, $campaign_id));
    }
    
    public function updateCampaignItem(string $account_id, string $campaign_id, string $item_id, array $data)
    {
        return $this->request('post', $data, sprintf($this->endpoint . DS . '%s/items/%s', $account_id, $campaign_id, $item_id));
    }
    
    public function deleteCampaignItem(string $account_id, string $campaign_id, string $item_id)
    {
        return $this->request('delete', "", sprintf($this->endpoint . DS . '%s/items/%s', $account_id, $campaign_id, $item_id));
    }
}