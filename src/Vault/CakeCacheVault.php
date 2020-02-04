<?php
/* 
 * @copyright (C) 2020 Michiel Keijts, Normit
 * 
 */

namespace TaboolaApi\Vault;

use TaboolaApi\Vault\VaultInterface;
use Cake\Cache\Cache;

class CakeCacheVault implements VaultInterface {
    /**
     * Name of the cache config
     * @var string
     */
    private $config = 'default';
    
    public function read(string $name) 
    {
        return Cache::read($name, $this->config);
    }
    
    public function write(string $name, $value) : bool
    {
        return Cache::write($name, $value, $this->config);
    }
    
    public function delete(string $name) : bool
    {
        return Cache::delete($name, $this->config);
    } 
    
    public function applyOptions(array $options) 
    {
        foreach ($options as $key=>$param) {
            $this->{$key} = $param;
        }
    }
}