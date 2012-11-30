<?php

namespace ConfigManager\Modules\System\Service;

use \ConfigManager\Interfaces\Manager as Manager;

class ServiceManager implements Manager
{
    protected $driver;
    protected $service;
    protected $addList = array();
    protected $setList = array();
    protected $replaceList = array();
    protected $id;

    public function __construct(Manager $driver = null)
    {
        $this->driver = $driver;
    }

    public function set($key, $value = false)
    {
        $this->write_config($key, $value, 'checkSet');
    }
    public function add($key, $value = false)
    {
        $this->write_config($key, $value, 'checkAdd');
    }
    public function replace($key, $value = false)
    {
        $this->write_config($key, $value, 'checkReplace');
    }
    public function get($key)
    {
        return $this->get_config($key);
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
        $this->delete_config($key);
    }
    public function merge(Manager $from)
    {
        $this->merge_config($from);
    }
    public function getId()
    {
        return $this->id;
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
        $this->save();
    }
    protected function assign($key, $value, $check)
    {
        if ($check == 'checkAdd')
        {
            $this->addList[$key] = $value;
        }
        elseif ($check == 'checkSet')
        {
            $this->setList[$key] = $value;
        }
        else
        {
            $this->replaceList[$key] = $value;
        }
    }
    protected function save()
    {
        
    }
    protected function get_config($key)
    {

    }
    protected function asArray_config()
    {

    }
    protected function exist_config($key)
    {

    }
    protected function delete_config($key)
    {

    }
    protected function merge_config(Manager $from)
    {
        $this->add($from->asArray());
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

    }
    protected function stopService()
    {

    }

}
