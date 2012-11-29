<?php

namespace ConfigManager\Modules\System\Simple;

use \ConfigManager\Exceptions\ItemNotExistException as ItemNotExistException;
use \ConfigManager\Exceptions\ItemAlreadyExistException as ItemAlreadyExistException;
use \ConfigManager\Modules\System\SafePersistent\SafePersistentManager as SafePersistentManager;

class SimpleManager extends SafePersistentManager
{
    public function __construct(Manager $driver = null)
    {
        parent::__construct($driver);
        $this->service = array();
        $this->id = '' . microtime(true) * 10000;
    }

    //---- Auto updated functions ----

    protected function get_config($key)
    {
        parent::get_config($key);
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
    protected function delete_config($key)
    {
        parent::delete_config($key);
        unset($this->service[$key]);
    }
    //---------------------------------
    protected function save()
    {
        if (!empty($this->addList))
        {
            foreach ($this->addList as $key => $value)
            {
                $this->service[$key] = $value;
            }
        }
        if (!empty($this->setList))
        {
            foreach ($this->setList as $key => $value)
            {
                $this->service[$key] = $value;
            }
        }
        if (!empty($this->replaceList))
        {
            foreach ($this->replaceList as $key => $value)
            {
                $this->service[$key] = $value;
            }
        }
        $this->addList = array();
        $this->setList = array();
        $this->replaceList = array();
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
