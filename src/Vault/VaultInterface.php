<?php
/* 
 * @copyright (C) 2020 Michiel Keijts, Normit
 * 
 */

namespace TaboolaApi\Vault;

interface VaultInterface {
    public function read(string $name);
    
    public function write(string $name, $value) : bool; 
    
    public function delete(string $name) : bool;
    
    public function applyOptions(array $options);
}