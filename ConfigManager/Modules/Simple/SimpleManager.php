<?php

namespace ConfigManager\Modules\Simple;

use \ConfigManager\Exceptions\ItemNotExistException as ItemNotExistException;
use \ConfigManager\Exceptions\ItemAlreadyExistException as ItemAlreadyExistException;
use \ConfigManager\Modules\NonPersistentService\NonPersistentServiceManager as NonPersistentServiceManager;

class SimpleManager extends NonPersistentServiceManager
{
    protected $id;

    public function __construct()
    {
        $this->service = array();
        $this->id = '' . microtime(true) * 10000;
    }

    //---- Auto updated functions ----

    protected function get_config($key, $check)
    {
        $this->checkExist($key);
        return $this->service[$key];
    }
    protected function asArray_config()
    {
        return $this->service;
    }
    protected function exist_config($key)
    {
        return isset($this->service[$key]);
    }
    protected function delete_config($key, $check)
    {
        $this->checkExist($key);
        unset($this->service[$key]);
    }
    //---------------------------------
    protected function assign($key, $value, $check)
    {
        $this->{$check}($key);
        $this->service[$key] = $value;
    }
    protected function checkReplace($key)
    {
        if (!$this->exist_config($key))
        {
            throw new ItemNotExistException($key);
        }
    }
    protected function checkAdd($key)
    {
        if ($this->exist_config($key))
        {
            throw new ItemAlreadyExistException($key);
        }
    }
    protected function checkSet()
    {
       
    }
    protected function checkExist($key)
    {
        if (!$this->exist_config($key))
        {
            throw new ItemNotExistException($key);
        }
    }
    protected function getConfig()
    {
        return $this->service;
    }
    protected function setConfig($config)
    {
        $this->service = $config;
    }
}
