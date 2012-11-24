<?php

namespace ConfigManager\Modules\Service;

use \ConfigManager\Interfaces\Manager as Manager;

class ServiceManager implements Manager
{
    protected $driver;
    protected $service;
    public function __construct(Manager $driver)
    {
        $service = 'php';
        if ($driver->exist('service'))
        {
            $service = $driver->get('service');
        }
        $managerClass = "\\ConfigManager\\Modules\\$service\\{$service}Manager";
        $this->driver = new $managerClass($driver);
    }
    protected function assignDriverValues(array $names)
    {
        foreach ($names as $name)
        {
            $this->assignDriverValue($name);
        }
    }
    protected function assignDriverValue($name)
    {
        if ($this->driver == null || !$this->driver->exist($name))
            return;
        $this->{$name} = $this->driver->get($name);
    }
    protected function startService()
    {
        return $this->service;
    }
    protected function stopService()
    {

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
    protected function assign($key, $value, $check)
    {
    }
    public function set($key, $value = false)
    {
        $this->write_config($key,$value,'checkSet');
    }
    public function add($key, $value = false)
    {
        $this->write_config($key,$value,'checkAdd');
    }
    public function replace($key, $value = false)
    {
        $this->write_config($key,$value,'checkReplace');
    }
    public function get($key)
    {
    }
    public function asArray()
    {
    }
    public function exist($key)
    {
    }
    public function delete($key)
    {
    }
    public function merge(Manager $from)
    {
    }
    public function getId()
    {
    }

}
