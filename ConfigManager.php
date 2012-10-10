<?php
define('ROOT',dirname(__file__) . DIRECTORY_SEPARATOR);
define('MANAGERS',ROOT.'extension_managers'.DIRECTORY_SEPARATOR);
define('UTILS',ROOT.'utils'.DIRECTORY_SEPARATOR);
define('LIBS',ROOT.'libs'.DIRECTORY_SEPARATOR);
define('INTERFACES',ROOT.'interfaces'.DIRECTORY_SEPARATOR);

include(INTERFACES.'ExtensionManager.php');
include(UTILS.'SafeIO.php');
include(UTILS.'DataFormat.php');
include(MANAGERS.'Manager.php');
include(MANAGERS.'CacheManager.php');
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
            require_once (MANAGERS."$name.php");
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