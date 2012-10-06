<?php
include('interfaces/ExtensionManager.php');
include('SafeIO.php');
include('extension_managers/Manager.php');
include('extension_managers/CacheManager.php');
class ConfigManager
{
    private static $extManagers;
    public static $parseCount=array();
    

    public static function getInstance($path,$cachePath='')
    {
        if (!isset(self::$extManagers[$path]))
        {
            $ar = explode('.', $path);
            $ext = $ar[count($ar) - 1];
            $name = ucfirst(strtolower($ext)) . 'Manager';
            require ("extension_managers/$name.php");
            $class=($cachePath != '')?new CacheManager($cachePath,new $name($path)):new $name($path);
            self::$extManagers[$path] = $class;
            return $class;
        }
        return self::$extManagers[$path];
    }
    public static function cleanInstance($path)
    {
        unset(self::$extManagers[$path]); 
    }
    public static function incrementParseCount($class)
    {
        if(isset(self::$parseCount[$class]))
        {
            self::$parseCount[$class]++;
        }    
        else
        {
            self::$parseCount[$class]=1;
        }
    }
}

?>