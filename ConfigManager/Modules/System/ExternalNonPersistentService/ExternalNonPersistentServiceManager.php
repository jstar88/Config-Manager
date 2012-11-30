<?php

namespace ConfigManager\Modules\System\ExternalNonPersistentService;
use \ConfigManager\Modules\System\NonPersistentService\NonPersistentServiceManager as NonPersistentServiceManager;
use \ConfigManager\Interfaces\Manager as Manager;
use \ConfigManager\Core\ConfigManager as ConfigManager;

class ExternalNonPersistentServiceManager extends NonPersistentServiceManager
{
    public function __construct(Manager $driver)
    {
        if ($driver !== null)
        {
            $service = 'php';
            if ($driver->exist('service'))
            {
                $service = $driver->get('service');
            }
            $path = $driver->get('dataPath');
            $path = substr_replace($path, $service, strrpos($path, '.') + 1);
            $this->driver = ConfigManager::getClass($path,  $driver);
        }
    }
}
