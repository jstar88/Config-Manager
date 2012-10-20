<?php

require_once (dirname(__file__) . str_replace('/',DIRECTORY_SEPARATOR,'/ConfigManager/Libs/splClassLoader/SplClassLoader.php'));

class ConfigManager
{
    private static function getInstance($path, $cachePath = null)
    {
        return new Stab(\ConfigManager\ConfigManager::getInstance($path, $cachePath));
    }
    private static function cleanInstace($path)
    {
        \ConfigManager\ConfigManager::cleanInstace($path);
    }
    public static function __callStatic($name, $arguments)
    {
        $spl = new SplClassLoader('ConfigManager');
        $spl->setIncludePath(dirname(__file__));
        $spl->register();
        $return = call_user_func_array(array("ConfigManager",$name), $arguments);
        $spl->unregister();
        return $return;
    }
}
class Stab
{
    private $manager;
    public function __construct(\ConfigManager\Interfaces\ExtensionManager $manager)
    {
        $this->manager = $manager;
    }
    public function __call($name, $arguments)
    {
        $spl = new SplClassLoader('ConfigManager');
        $spl->setIncludePath(dirname(__file__));
        $spl->register();
        $return = call_user_func_array(array($this->manager, $name), $arguments);
        $spl->unregister();
        return $return;
    }
}

?>