<?php
/* 
 * @copyright (C) 2020 Michiel Keijts, Normit
 * 
 * @Licence MIT
 * 
 */

namespace TaboolaApi\Client;

use GuzzleHttp\Client;
use TaboolaApi\Client\Authentication\AuthenticationInterface;
use TaboolaApi\Client\ClientCredentialsAuthentication;
use TaboolaApi\Client\PasswordAuthentication;
use Psr\Http\Message\ResponseInterface;

/**
 * For full list of fields see
 *  https://backstage-api.readme.io/reference#campaign-fields-overview
 * 
 * Compatible with Taboola API 1.0
 */
class TaboolaClient {
    /**
     * The 1.0 endpoint
     * @var string
     */
    private $_api_url = " https://backstage.taboola.com/backstage/api/1.0";
    /**
     * 
     * @var AuthenticationInterface
     */
    private $_authentication;
    
    /**
     * Guzzle Client
     * @var Client; 
     */
    private $_client;
    
    /**
     * @param array $options
     */
    public function __construct(array $options) 
    {
        $this->createAuthentication($options);
    }
    
    /**
     * Create the Authenthication
     * @param array $options
     */
    private function createAuthentication(array $options) {
        if (!array_key_exists('className', $options)) {
            if (isset($options['username']) && isset($options['password'])) {
                $options['className'] = PasswordAuthentication::class;
            } else {
                $options['className'] = ClientCredentialsAuthentication::class;
            }
        }
        
        // set defaults
        $options = $options + ['username' => "", 'password' => "", "client_id" => "", "client_secret"=>""];
        
        $this->_authentication = new $options['className']($options['client_id'],$options['client_secret'],$options['username'],$options['password'], $options);
    }
    
    /**
     * Main Request 
     * @param string $endpoint
     * @param array $data
     */
    public function doRequest(string $endpoint, array $data = [], string $method = 'post', bool $raw = FALSE)
    {
        $client = $this->getClient();
        
        if (!method_exists($client, $method)) {
            throw new Exception("Invalid or unsupported HTTP method");
        }
        
        $i=0;
        while ($i++ < $this->max_retries) {
            $response = $this->execute($endpoint, $data, $method);
            
            if ($response->getStatusCode() == 401) {
                // revoking accestoken leads to new Authentication Request
                $this->getAuthentication()->revokeAccesToken();
            } else {
                break;
            }
        }
        
        if ($raw) {
            return $response;
        }
        
        return $this->parseResponse($response);
    }
    
    /**
     * Execute the request
     * @param string $endpoint
     * @param array $data
     * @param string $method
     * @return ResponseInterface
     */
    private function execute(string $endpoint, array $data = [], string $method) : ResponseInterface
    {
        if (!isset($options['headers'])) {
            $options['headers'] = [];
        }
        
        $options['headers'] = $options['headers'] + [
            'Authentication' => 'Bearer ' . $this->getAuthentication()->getAccesToken(),
            'Content-Type' => 'application/json'
        ];
        
        $options['body'] = $data;
                
        $response = call_user_func_array([$client, $method], [$endpoint, $options]);
        
        return $response;
    }
    
    /**
     * Return the Guzzle HTTP client (initiate if not existing)
     * @return Client
     */
    private function getClient() : Client
    {
        if (empty($this->_client)) {
            $this->_client = new Client(['base_uri'=>$this->_api_url, 'timeout'=>60, 'allow_redirects'=>true]);
        }
        
        return $this->_client;
    }
   
    /**
     * 
     * @return AuthenticationInterface
     */
    private function getAuthentication() : AuthenticationInterface
    {
        return $this->_authentication;
    }
}