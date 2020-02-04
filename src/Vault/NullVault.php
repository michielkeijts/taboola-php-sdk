<?php
/* 
 * @copyright (C) 2020 Michiel Keijts, Normit
 * 
 */

namespace TaboolaApi\Vault;

use TaboolaApi\Vault\VaultInterface;

class NullVault implements VaultInterface {
    public function read(string $name) 
    {
        return NULL;
    }
    
    public function write(string $name, $value) : bool
    {
        return true;
    }
    
    public function delete(string $name) : bool
    {
        return true;
    }    
    
    public function applyOptions(array $options) 
    {
        return;
    }
}