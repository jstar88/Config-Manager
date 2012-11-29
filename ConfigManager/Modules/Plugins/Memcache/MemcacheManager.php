<?php

namespace \ConfigManager\Modules\Plugins\Memcache;

use \ConfigManager\Exceptions\ItemNotExistException as ItemNotExistException;
use \ConfigManager\Exceptions\ItemAlreadyExistException as ItemAlreadyExistException;
use \ConfigManager\Modules\System\SafePersistent\SafePersistentManager as SafePersistentManager;
use \ConfigManager\Interfaces\Manager as Manager;

class MemcacheManager extends SafePersistentManager
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
        $this->assignDriverValues($assign);
    }
    protected function checkService()
    {
        if (!empty($this->service))
            return;

        $memcache = new Memcache();
        $memcache->connect($this->host, $this->port);
        $this->service = $memcache;
    }

    protected function save()
    {
        if (!empty($this->addList))
        {
            foreach ($this->addList as $key => $value)
            {
                $this->service->add($key, $value, $this->flag, $this->expire);
            }
        }
        if (!empty($this->setList))
        {
            foreach ($this->setList as $key => $value)
            {
                $this->service->set($key, $value, $this->flag, $this->expire);
            }
        }
        if (!empty($this->replaceList))
        {
            foreach ($this->replaceList as $key => $value)
            {
                $this->service->replace($key, $value, $this->flag, $this->expire);
            }
        }

    }
    protected function get_config($key)
    {
        parent::get_config($key);
        return $this->service->get($key);
    }
    protected function asArray_config()
    {
        //to do
    }
    protected function exist_config($key)
    {
        if ($this->service->add($key, false, $this->flag, $this->expire))
        {
            $this->delete_config($key);
            return false;
        }
        return true;

    }
    protected function delete_config($key)
    {
        parent::delete_config($key);
        $this->service->delete($key);
    }
    public function __destruct()
    {
        $this->service->close();
    }
}
