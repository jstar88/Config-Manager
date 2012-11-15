<?php

namespace ConfigManager\Modules\Simple;

use \ConfigManager\Interfaces\Manager as Manager;
use \ConfigManager\Exceptions\ItemNotExistException as ItemNotExistException;
use \ConfigManager\Exceptions\ItemAlreadyExistException as ItemAlreadyExistException;

class SimpleManager implements Manager
{
    private $config;
    protected $id;

    public function __construct()
    {
        $this->config = array();
        $this->id = '' . microtime(true) * 10000;
    }

    //----  methods of interfaces ----
    public function add($key, $value = false)
    {
        $this->write_config($key, $value, 'checkAdd');
    }
    public function set($key, $value = false)
    {
        $this->write_config($key, $value, 'checkSet');
    }
    public function replace($key, $value = false)
    {
        $this->write_config($key, $value, 'checkReplace');
    }
    public function get($key)
    {
        return $this->get_config($key, 'checkExist');
    }
    public function asArray()
    {
        return $this->asArray_config();
    }
    public function exist($key)
    {
        return $this->exist_config($key);
    }
    public function delete($key)
    {
        $this->delete_config($key, 'checkExist');
    }
    public function merge(Manager $from)
    {
        $this->add($from->asArray());
    }
    public function getId()
    {
        return $this->id;
    }
    //--------------------------------
    //---- Auto updated functions ----

    protected function get_config($key, $check)
    {
        $this->{$check}($key);
        return $this->config[$key];
    }
    protected function asArray_config()
    {
        return $this->config;
    }
    protected function exist_config($key)
    {
        return isset($this->config[$key]);
    }
    protected function delete_config($key, $check)
    {
        $this->{$check}($key);
        unset($this->config[$key]);
    }
    protected function write_config($config_name, $config_value, $check)
    {

        if (is_array($config_name) || is_object($config_name))
        {
            foreach ($config_name as $key => $value)
            {
                $this->assign($key, $value, $check);
            }
        }
        else
        {
            $this->assign($config_name, $config_value, $check);
        }
    }
    //---------------------------------
    protected function assign($key, $value, $check)
    {
        $this->{$check}($key);
        $this->config[$key] = $value;
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
        return $this->config;
    }
    protected function setConfig($config)
    {
        $this->config = $config;
    }
}
