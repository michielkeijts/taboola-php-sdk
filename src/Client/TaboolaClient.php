<?php
/*
 * @copyright (C) 2020 Michiel Keijts, Normit
 *
 * @Licence MIT
 *
 */

namespace TaboolaApi\Client;

use GuzzleHttp\Client;
use TaboolaApi\Client\Authentication\BaseAuthentication;
use TaboolaApi\Client\Authentication\ClientCredentialsAuthentication;
use TaboolaApi\Client\Authentication\PasswordAuthentication;
use Psr\Http\Message\ResponseInterface;
use TaboolaApi\Client\Responses\Response;
use Exception;

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
    private $_api_url = "https://backstage.taboola.com/backstage/api/1.0/";
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
     *
     * @var integer
     */
    private $max_retries = 3;

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
        $options = $options + ['username' => "", 'password' => "", "client_id" => "", "client_secret"=>"", "max_retries"=>3];

        $this->_authentication = new $options['className']($options['client_id'],$options['client_secret'],$options['username'],$options['password'], $options);

        $this->max_retries = $options['max_retries'];
    }

    /**
     * Main Request
     * @param string $endpoint
     * @param array $data
     */
    public function doRequest(string $endpoint, $data = "", string $method = 'post', bool $raw = FALSE)
    {
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

        if (!isset($response) || $response instanceof ResponseInterface) {
            return;
        }

        if ($raw) {
            return $response;
        }

        return new Response($response);
    }

    /**
     * Execute the request
     * @param string $endpoint
     * @param array $data
     * @param string $method
     * @return ResponseInterface
     */
    private function execute(string $endpoint, $data = "", string $method = "") : ResponseInterface
    {
        $client = $this->getClient();
        $options = [];
        if (!isset($options['headers'])) {
            $options['headers'] = [];
        }

        $options['headers'] = $options['headers'] + [
            'Authorization' => 'Bearer ' . $this->getAuthentication()->getAccesToken(),
            'Content-Type' => 'application/json'
        ];

        if ($method !== 'get') {
            if (is_array($data) && $method !== 'get') {
                $options['form_params'] = $data;
                $options['headers']['Content-Type'] = 'application/x-www-form-urlencoded';
            } elseif(!empty($data)) {
                $options['body'] = $data;
            }
        } else {
            if (!empty($data)) {
                $options['query'] = $data;
            }
        }

        try {
            $response = call_user_func_array([$client, $method], [$endpoint, $options]);
            return $response;
        } catch (Exception $e) {
            echo "";
        }

        return new \Cake\Http\Response();
    }

    /**
     * Return the Guzzle HTTP client (initiate if not existing)
     * @return Client
     */
    private function getClient() : Client
    {
        if (empty($this->_client)) {
            $this->_client = new Client([
                'base_uri'=>$this->_api_url,
                'timeout'=>60,
                'allow_redirects'=>true,
                'http_errors' => false,
                CURLOPT_FAILONERROR => 0
            ]);
        }

        return $this->_client;
    }

    /**
     *
     * @return BaseAuthentication
     */
    private function getAuthentication() : BaseAuthentication
    {
        return $this->_authentication;
    }
}
