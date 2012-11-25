<?php

namespace \ConfigManager\Modules\PersistentService;
use \ConfigManager\Modules\Service\ServiceManager as ServiceManager;

class PersistentServiceManager extends ServiceManager
{
    public function set($key, $value = false)
    {
        $this->checkService();
        $this->set_config($key, $value = false);
    }
    public function add($key, $value = false)
    {
        $this->checkService();
        $this->add_config($key, $value = false);
    }
    public function replace($key, $value = false)
    {
        $this->checkService();
        $this->replace_config($key, $value = false);
    }
    public function get($key)
    {
        $this->checkService();
        return $this->get_config($key);
    }
    public function asArray()
    {
        $this->checkService();
        return $this->asArray_config();
    }
    public function exist($key)
    {
        $this->checkService();
        return $this->exist_config($key);
    }
    public function delete($key)
    {
        $this->checkService();
        $this->delete_config($key);

    }
    public function merge(Manager $from)
    {
        $this->checkService();
        $this->merge_config($from);
    }
    public function getId()
    {
    }
    protected function checkService()
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
