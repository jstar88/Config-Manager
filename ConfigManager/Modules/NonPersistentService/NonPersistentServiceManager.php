<?php

namespace \ConfigManager\Modules\NonPersistentService;
use \ConfigManager\Modules\Service\ServiceManager as ServiceManager;
use \ConfigManager\Interfaces\Manager as Manager;

class NonPersistentServiceManager extends ServiceManager
{
    public function __construct(Manager $driver)
    {
        parent::__construct($driver);
    }
    public function set($key, $value = false)
    {
        $service = $this->startService();
        parent::set($key,$value);
        $this->stopService();
    }
    public function add($key, $value = false)
    {
        $this->startService();
        parent::add($key,$value);
        $this->stopService();
    }
    public function replace($key, $value = false)
    {
        $this->startService();
        parent::replace($key,$value);
        $this->stopService();
    }
    public function get($key)
    {
        $this->startService();
        $value = parent::get($key);
        $this->stopService();
        return $value;
    }
    public function asArray()
    {
        $this->startService();
        $config = parent::asArray();
        $this->stopService();
        return $config;
    }
    public function exist($key)
    {
        $this->startService();
        $exist = parent::exist($key);
        $this->stopService();
        return $exist;
    }
    public function delete($key)
    {
        $this->startService();
        parent::delete($key);
        $this->stopService();
    }
    public function merge(Manager $from)
    {
        $this->startService();
        parent::merge($from);
        $this->stopService();
    }

}
