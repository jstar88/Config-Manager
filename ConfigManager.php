<?php

require_once (dirname(__file__) . str_replace('/', DIRECTORY_SEPARATOR, '/ConfigManager/Libs/splClassLoader/SplClassLoader.php'));

class ConfigManager
{
    private $manager;
    public function __construct($manager)
    {
        $this->manager = $manager;
    }
    public function __call($name, $arguments)
    {
        return self::autoloadedCall(array($this->manager, $name), $arguments);
    }
    public static function __callStatic($name, $arguments)
    {
        return self::autoloadedCall(array("ConfigManager", $name), $arguments);
    }
    private static function getInstance($source, $cache = null)
    {
        return new ConfigManager(\ConfigManager\Core\ConfigManager::getInstance($source, $cache));
    }
    private static function cleanInstace($source)
    {
        \ConfigManager\Core\ConfigManager::cleanInstace($source);
    }
    private static function showDebug()
    {
        \ConfigManager\Core\ConfigManager::showDebug();
    }
    private static function autoloadedCall($callBack, $args)
    {
        $spl = new SplClassLoader('ConfigManager');
        $spl->setIncludePath(dirname(__file__));
        $spl->register();
        $return = call_user_func_array($callBack, $args);
        $spl->unregister();
        return $return;
    }

}
