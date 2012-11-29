<?php

namespace ConfigManager\Core;

use \ConfigManager\Interfaces\Manager as Manager;
use \ConfigManager\Exceptions\IllegalArgumentException as IllegalArgumentException;
use \ConfigManager\Modules\System\Simple\SimpleManager as SimpleManager;

define(__namespace__ . '\LIBS', dirname(__dir__ ) . DIRECTORY_SEPARATOR . 'LIBS' . DIRECTORY_SEPARATOR);
/**
 * ConfigManager
 * 
 * @package Config-Manager
 * @author Covolo Nicola
 * @copyright 2012
 * @version 4.0.6
 * @access public
 */
class ConfigManager
{
    private static $extManagers;
    public static $debug = array();


    /**
     * ConfigManager::getInstance()
     * This function choose and return the correct manager and instantiating it if required.
     * The arguments can be string paths,pointing the data file.
     * Perhaps they can also be objects that implements interface Manager, on that way they can be used to:<br> 
     * 1)point the data file, having a key called 'dataPath';<br>
     * 2)be a driver, having the key 'dataPath' and some others required from the manager to work;<br>
     * 3)a driver of driver,having the key 'driverPath' as next driver. Note that only the last driver must have the key 'dataPath';<br> 
     * 
     * @param mixed $source <br>
     * a string path where data file is located or an object that implements interface Manager
     * @param mixed $cache <br>
     * a string path where cache file will be located or an object that implements interface Manager
     * @return Manager $manager <br>
     * return an singleton istance to manage the data file.
     * @example C:\Users\nicola user\Config-Manager\tutorials\simple.php
     */
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


    /**
     * ConfigManager::getInstaceFromObject()
     * Return the manager class with singleton pattern  
     * @param Manager $source
     * @param Manager $cache
     * @return Manager
     */
    private static function getInstaceFromObject(Manager $source, Manager $cache = null)
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
            $class = new ExceptionDecorator($class);
            self::$extManagers[$id] = $class;
            return $class;
        }
        return self::$extManagers[$id];
    }


    /**
     * ConfigManager::cleanInstance() 
     * Delete the singleton with id as argument
     * @param string $id
     * @return null
     */
    public static function cleanInstance($id)
    {
        unset(self::$extManagers[$id]);
    }


    /**
     * ConfigManager::debug()
     * Function used to store information about the script flush 
     * @param string $class <br>
     * The class where this function is called
     * @param string $info <br>
     * Generic information about the state
     * @return null
     */
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


    /**
     * ConfigManager::showDebug()
     * Show the debug content in php default output 
     * @return null
     */
    public static function showDebug()
    {
        var_export(self::$debug);
    }


    /**
     * ConfigManager::getDrivenManagerClass() 
     * Instantiate the manager class with right driver
     * @param Manager $driver
     * @return Manager
     */
    private static function getDrivenManagerClass(Manager $driver)
    {
        $driver = self::getDrivenDriverClass($driver);
        return self::getClass($driver->get('dataPath'), $driver);
    }


    /**
     * ConfigManager::getDrivenDriverClass()
     * Recursively get the last driver of chains
     * @param Manager $driver
     * @return Manager
     */
    private static function getDrivenDriverClass(Manager $driver)
    {
        if ($driver->exist('driverPath'))
        {
            $driver = self::getDrivenManagerClass(self::getClass($driver->get('driverPath'), $driver));
        }
        return $driver;
    }


    /**
     * ConfigManager::getClass()
     * Function used in the project to instantiate the rigth manager from a path 
     * @param string $path <br>
     * The data path 
     * @param Manager $driver <br>
     * The corrispective driver
     * @return Manager
     */
    public static function getClass($path, Manager $driver)
    {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $ext = ucfirst(strtolower($ext));
        $managerClass = "\\ConfigManager\\Modules\\Plugins\\$ext\\{$ext}Manager";
        return new $managerClass($driver);
    }
}
