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
    
    public function getStatusCode()
    {
        return $this->original->getStatusCode();
    }
    
    /**
     * Return a result or empty array
     * @param int $id (default -1, return all results)
     * @return array
     */
    public function getResults($id = -1)
    {
        if ($id < 0) {
            return $this->results;
        }
        
        if (!array_key_exists($id, $this->results)) {
            return [];
        }
        
        return $this->results[$id];
    }
    
    /**
     * Return the messag
     * @return string
     */
    public function getErrorMessage() :string
    {
        $message = "";
        if (isset($this->responseContent) && isset($this->responseContent->message)) {
            $message = $this->responseContent->message;
        }
        
        return $message;
    }
    
    /**
     * Return errors per field: field=>$message
     * @return array
     */
    public function getErrors() : array
    {
        $errors = [];
        if (isset($this->responseContent) && isset($this->responseContent->message)) {
            if (isset($this->responseContent->offending_field)) {
                $errors[$this->responseContent->offending_field] = [$this->responseContent->message];
            } else {
                $errors[] = $this->responseContent->message;
            }
        }
        
        return $errors;
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
        
        if (isset($this->responseContent->metadata)) {
            $this->results = $this->responseContent->results;
            $this->count = $this->responseContent->metadata->count;
            $this->total = $this->responseContent->metadata->total;
        } else {
            if (isset($this->responseContent->http_status) && $this->responseContent->http_status !== 200) {
                $this->results = [];
                $this->count = 0;
                $this->total = 0;
                $this->message= $this->responseContent->message;
            } else {
                $this->results = [$this->responseContent];
                $this->count = 1;
                $this->total = 1;
            }
        }
    }
}