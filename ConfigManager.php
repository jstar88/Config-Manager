<?php

require_once ('Autoloaded.php');

class ConfigManager extends Autoloaded
{
    public function __construct($manager)
    {
        parent::__construct($manager);
    }
    protected static function getInstance($source, $cache = null)
    {
        return new ConfigManager(\ConfigManager\Core\ConfigManager::getInstance($source, $cache));
    }
    protected static function cleanInstace($source)
    {
        \ConfigManager\Core\ConfigManager::cleanInstace($source);
    }
    protected static function showDebug()
    {
        \ConfigManager\Core\ConfigManager::showDebug();
    }

}
