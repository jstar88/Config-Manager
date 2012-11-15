<?php

namespace \ConfigManager\Modules\Memcache;

use \ConfigManager\Exceptions\ItemNotExistException as ItemNotExistException;
use \ConfigManager\Exceptions\ItemAlreadyExistException as ItemAlreadyExistException;
use \ConfigManager\Modules\File\FileManager as FileManager;

class MemcacheManager extends FileManager
{
    protected $host, $port, $flag, $expire;
    public function __construct(Manager $driver)
    {
        parent::__construct($driver);
        $assign = array(
            'host',
            'port',
            'flag',
            'expire');
        foreach ($assign as $name)
        {
            $this->assignDriverValue($name);
        }
    }
    protected function onlyOpenConfig($path)
    {
        $memcache = new Memcache();
        $memcache->connect($this->host, $this->port);
        return $memcache;
    }
    protected function onlySaveConfig($content, $path)
    {
        //do nothing
    }

    protected function assign($key, $value, $can_add)
    {
        if ($can_add)
        {
            parent::getConfig()->add($key, $value, $this->flag, $this->expire);
        }
        else
        {
            parent::getConfig()->replace($key, $value, $this->flag, $this->expire);
        }
    }
    protected function get_config($key)
    {
        return parent::getConfig()->get($key);
    }
    protected function asArray_config()
    {
        return $this->config;
    }
    protected function exist_config($key)
    {
        if (parent::getConfig()->add($key, false, $this->flag, $this->expire))
        {
            $this->delete_config($key);
            return false;
        }
        return true;

    }
    protected function delete_config($key)
    {
        parent::getConfig()->delete($key);
    }
    public function __destruct()
    {
        parent::getConfig()->close();
    }
}
