<?php

namespace ConfigManager\Core;

use \ConfigManager\Interfaces\Manager as Manager;
use \ConfigManager\Exceptions\IllegalArgumentException as IllegalArgumentException;
use \ConfigManager\Modules\Php\PhpManager as PhpManager;
use \ConfigManager\Modules\Simple\SimpleManager as SimpleManager;

define(__namespace__ . '\LIBS', dirname(__file__) . DIRECTORY_SEPARATOR . 'Libs' . DIRECTORY_SEPARATOR);

class ConfigManager
{
    private static $extManagers;
    public static $debug = array();

    public static function getInstance($source, $cache = null)
    {
        if (is_string($source))
        {
            $tmp = new SimpleManager();
            $tmp->set('dataPath', $source);
            $source = $tmp;
        }
        if (is_string($cache))
        {
            $tmp = new SimpleManager();
            $tmp->set('dataPath', $cache);
            $cache = $tmp;
        }
        return self::getInstaceFromObject($source, $cache);
    }
    public static function getInstaceFromObject(Manager $source, Manager $cache = null)
    {
        $id = ($cache == null) ? $source->getId() : $source->getId() . $cache->getId();
        if (!isset(self::$extManagers[$id]))
        {
            $class = self::getDrivenManagerClass($source);
            if ($cache !== null)
            {
                $cache = self::getDrivenManagerClass($cache);
                $class = new CacheManager($class, $cache);
            }
            self::$extManagers[$id] = $class;
            return $class;
        }
        return self::$extManagers[$id];
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
        if ($info != '')
        {
            self::$debug[$class]['trace'][] = $info;
        }
    }
    public static function showDebug()
    {
        var_export(self::$debug);
    }
    private static function getDrivenManagerClass(Manager $driver)
    {
        $driver= self::getDrivenDriverClass($driver);
        return self::getManagerClass($driver);
    }
    private static function getDrivenDriverClass(Manager $driver)
    {
        if ($driver->exist('driverPath'))
        {
            $driver = self::getDrivenManagerClass(self::getDriverClass($driver));
        }
        return $driver;
    }
    private static function getDriverClass(Manager $driver)
    {
        $ext = self::getExtension($driver->get('driverPath'));
        $managerClass = "\\ConfigManager\\Modules\\$ext\\{$ext}Manager";
        return new $managerClass($driver);    
    }
    private static function getManagerClass(Manager $driver)
    {
        $ext = self::getExtension($driver->get('dataPath'));
        $managerClass = "\\ConfigManager\\Modules\\$ext\\{$ext}Manager";
        return new $managerClass($driver);
    }
    private static function getExtension($path)
    {
        $ext = pathinfo($path,PATHINFO_EXTENSION);
        $name = ucfirst(strtolower($ext));
        return $name;
    }
}
