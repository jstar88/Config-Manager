<?php

namespace \ConfigManager\Modules\PersistentService;
use \ConfigManager\Modules\NonPersistentService\NonPersistentServiceManager as NonPersistentServiceManager;
use \ConfigManager\Interfaces\Manager as Manager;

class PersistentServiceManager extends NonPersistentServiceManager
{
    public function __construct(Manager $driver)
    {
        parent::__construct($driver);
    }
    protected function startService()
    {
        $this->checkService();
    }
    protected function checkService()
    {
        
    }
}
