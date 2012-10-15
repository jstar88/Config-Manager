<?php

define('ROOT', dirname(__file__) . DIRECTORY_SEPARATOR);
define('MANAGERS', ROOT . 'extension_managers' . DIRECTORY_SEPARATOR);
define('UTILS', ROOT . 'utils' . DIRECTORY_SEPARATOR);
define('LIBS', ROOT . 'libs' . DIRECTORY_SEPARATOR);
define('INTERFACES', ROOT . 'interfaces' . DIRECTORY_SEPARATOR);
define('EXCEPTIONS', ROOT . 'exceptions' . DIRECTORY_SEPARATOR);

include (INTERFACES . 'ExtensionManager.php');
include (UTILS . 'SafeIO.php');
include (UTILS . 'DataFormat.php');
include (MANAGERS . 'Manager.php');
include (MANAGERS . 'PhpManager.php');
include (ROOT . 'CacheManager.php');
include (EXCEPTIONS . 'IllegalArgumentException.php');
include (ROOT . 'ConfigFile.php');
class ConfigManager
{
    private static $extManagers;
    public static $parseCount = array();


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
    }
    private function getDriverClass(ConfigFile $source)
    {
        if(empty($source->driverPath)) return null;
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
    public static function incrementParseCount($class)
    {
        if (isset(self::$parseCount[$class]))
        {
            self::$parseCount[$class]++;
        } else
        {
            self::$parseCount[$class] = 1;
        }
    }
    private static function getManagerClass($path, ExtensionManager $driver = null)
    {
        $ar = explode('.', $path);
        $count = count($ar);
        if ($count < 2)
        {
            throw new IllegalArgumentException($path,
                'folder/[configName].[configExtension]');
        }
        $managerClass = self::getManagerName($ar[$count - 1]);
        if ($driver === null)
            $driver = new PhpManager($path);
        return new $managerClass($path, $driver);
    }
    private static function getManagerName($ext)
    {
        $name = ucfirst(strtolower($ext)) . 'Manager';
        require_once (MANAGERS . "$name.php");
        return $name;
    }
}

?>