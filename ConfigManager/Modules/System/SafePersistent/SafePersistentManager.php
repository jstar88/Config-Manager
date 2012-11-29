<?php

namespace ConfigManager\Modules\System\SafePersistent;

use \ConfigManager\Exceptions\ItemNotExistException as ItemNotExistException;
use \ConfigManager\Exceptions\ItemAlreadyExistException as ItemAlreadyExistException;
use \ConfigManager\Modules\System\PersistentService\PersistentServiceManager as PersistentServiceManager;

class SafePersistentManager extends PersistentServiceManager
{

    public function __construct(Manager $driver = null)
    {
        parent::__construct($driver);
    }

    //---- Auto updated functions ----

    protected function get_config($key)
    {
        $this->checkExist($key);
    }
    protected function delete_config($key)
    {
        $this->checkExist($key);
    }
    //---------------------------------
    protected function assign($key, $value, $check)
    {
        $this->{$check}($key);
        parent::assign($key,$value,$check);
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
}
