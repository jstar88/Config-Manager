<?php

namespace ConfigManager;

use \ConfigManager\Interfaces\ExtensionManager as ExtensionManager;
use \ConfigManager\Exceptions\IllegalArgumentException as IllegalArgumentException;
use \ConfigManager\Managers\PhpManager as PhpManager;

define(__NAMESPACE__.'\LIBS',dirname(__FILE__).DIRECTORY_SEPARATOR.'Libs'.DIRECTORY_SEPARATOR );

class ConfigManager
{
    private static $extManagers;
    public static $debug = array();
    
    public static function getInstance($path, $cachePath = null)
    {
        if (is_object($path))
            return self::getInstaceFromObject($path, $cachePath);
        if (!isset(self::$extManagers[$path]))
        {

            $class = self::getManagerClass($path);
            if ($cachePath !== null)
            {
                $cache = self::getManagerClass($cachePath);
                $class = new CacheManager($class, $cache);
            }
            self::$extManagers[$path] = $class;
            return $class;
        }
        return self::$extManagers[$path];
    }
    public static function getInstaceFromObject(ConfigFile $source, ConfigFile $cache = null)
    {
        $id = ($cache == null) ? $source->getId() : $source->getId() . $cache->getId();
        if (!isset(self::$extManagers[$id]))
        {
            $class = self::getManagerClass($source->dataPath, self::getDriverClass($source));
            if ($cache !== null)
            {
                $cache = self::getManagerClass($cache->dataPath, self::getDriverClass($cache));
                $class = new CacheManager($class, $cache);
            }
            self::$extManagers[$id] = $class;
            return $class;
        }
        return self::$extManagers[$path];
    }
    private function getDriverClass(ConfigFile $source)
    {
        if (empty($source->driverPath))
            return null;
        $driver = self::getManagerClass($source->driverPath);
        if (!empty($source->cacheDriverPath))
        {
            $driverCache = self::getManagerClass($source->cacheDriverPath);
            $driver = new CacheManager($driver, $driverCache);
        }
        return $driver;
    }
    public static function cleanInstance($path)
    {
        unset(self::$extManagers[$path]);
    }
    public static function debug($class, $info = '')
    {
        if (isset(self::$debug[$class]))
        {
            self::$debug[$class]['count']++;
        }
        else
        {
            self::$debug[$class]['count'] = 1;
        }
        if($info != '')
        {
            self::$debug[$class]['trace'][] = $info;
        }
    }
    private static function getManagerClass($path, ExtensionManager $driver = null)
    {
        $ar = explode('.', $path);
        $count = count($ar);
        if ($count < 2)
        {
            throw new IllegalArgumentException($path, 'folder/[configName].[configExtension]');
        }
        $managerClass = self::getManagerName($ar[$count - 1]);
        if ($driver === null)
            $driver = new PhpManager($path);
        $managerClass = "\\ConfigManager\\Managers\\$managerClass";
        return new $managerClass($path, $driver);
    }
    private static function getManagerName($ext)
    {
        $name = ucfirst(strtolower($ext)) . 'Manager';
        //require_once (MANAGERS . "$name.php");
        return $name;
    }
}

?>