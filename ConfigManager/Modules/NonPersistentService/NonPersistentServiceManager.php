<?php

namespace \ConfigManager\Modules\NonPersistentService;
use \ConfigManager\Modules\Service\ServiceManager as ServiceManager;

class NonPersistentServiceManager extends ServiceManager
{
    public function set($key, $value = false)
    {
        $service = $this->startService();
        $this->stopService();
    }
    public function add($key, $value = false)
    {
        $this->startService();
        $this->add_config($key, $value = false);
        $this->stopService();
    }
    public function replace($key, $value = false)
    {
        $this->startService();
        $this->replace_config($key, $value = false);
        $this->stopService();
    }
    public function get($key)
    {
        $this->startService();
        $value = $this->get_config($key);
        $this->stopService();
        return $value;
    }
    public function asArray()
    {
        $this->startService();
        $config = $this->asArray_config();
        $this->stopService();
        return $config;
    }
    public function exist($key)
    {
        $this->startService();
        $exist = $this->exist_config($key);
        $this->stopService();
        return $exist;
    }
    public function delete($key)
    {
        $this->startService();
        $this->delete_config($key);
        $this->stopService();
    }
    public function merge(Manager $from)
    {
        $this->startService();
        $this->merge_config($from);
        $this->stopService();
    }
    public function getId()
    {
    }

    protected function set_config($key, $value = false)
    {

    }
    protected function add_config($key, $value = false)
    {

    }
    protected function replace_config($key, $value = false)
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

    }

}
