<?php
namespace ConfigManager\Interfaces;

interface ExtensionManager
{
    public function set($key, $value=false);
    public function add($key, $value=false);
    public function get($key);
    public function asArray();
    public function exist($key);
    public function delete($key);

}

?>