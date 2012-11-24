<?php

namespace \ConfigManager\Modules\PersistentService;
use \ConfigManager\Modules\Service\ServiceManager as ServiceManager;

class PersistentServiceManager extends ServiceManager
{
    public function __construct()
    {
        $this->startService();
    }
    public function __destruct()
    {
        $this->stopService();
    }
}