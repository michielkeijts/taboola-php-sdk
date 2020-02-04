<?php
/* 
 * @copyright (C) 2020 Michiel Keijts, Normit
 * 
 */


namespace TaboolaApi\Client\Responses;

use Psr\Http\Message\ResponseInterface;


class Response {

    /**
     * The original response
     * @var ResponseInterface
     */
    private $original;
    
    /**
     * 
     * stdClass
     */
    public $responseContent;
    
    /**
     * @var array
     */
    public $results = [];
    
    /**
     * Number of results in total
     * @var integer
     */
    public $total = 0;
            
    /**
     * Number of reults in this set
     * @var integer
     */
    public $count = 0;
        
    /**
     * 
     * @param ResponseInterface $original
     */
    public function __construct(ResponseInterface $original) {
        $this->original = $original;
        $this->parseResponse($original);
    }
    
    /**
     * Parse the response
     * @param ResponseInterface $original
     * @return type
     */
    private function parseResponse (ResponseInterface $original)
    {
        $body = $original->getBody()->getContents();
        
        $this->responseContent = json_decode($body);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return;
        }
        
        if ($this->responseContent->metadata) {
            $this->results = $this->responseContent->results;
            $this->count = $this->responseContent->count;
            $this->total = $this->responseContent->total;
        } else {
            $this->results = [$this->responseContent];
            $this->count = 1;
            $this->total = 1;
        }
    }
}