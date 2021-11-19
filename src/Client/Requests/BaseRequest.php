<?php
/* 
 * @copyright (C) 2020 Michiel Keijts, Normit
 * 
 * 
 * @licence MIT
 */

namespace TaboolaApi\Client\Requests;

use TaboolaApi\Client\TaboolaClient;
use TaboolaApi\Client\Responses\Response;

abstract class BaseRequest {
    
    /**
     * The Endpoint URL
     * @var string 
     */
    protected $endpoint = "";
    
    /**
     *
     * @var client
     */
    protected $client;

    public function __construct(TaboolaClient $client) 
    {
        $this->client = $client;
    }
    
    public function request(string $method = 'get', $data = "", string $endpoint = "") : Response
    {
        $endpoint = empty($endpoint) ? $this->endpoint : $endpoint;
        
        if (!empty($data) && $method!=='get') {
            $data = json_encode($data);
        }
        
        return $this->client->doRequest($endpoint, $data, $method , FALSE);
    }
}