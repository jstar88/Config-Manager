<?php

namespace ConfigManager\Modules\System\PersistentService;

use \ConfigManager\Modules\System\NonPersistentService\NonPersistentServiceManager as NonPersistentServiceManager;
use \ConfigManager\Interfaces\Manager as Manager;

class PersistentServiceManager extends NonPersistentServiceManager
{
    public function __construct(Manager $driver = null)
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
