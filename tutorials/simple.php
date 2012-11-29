<?php

//get the json manager from string path
$manager = ConfigManager::getInstance('config.json');

//get the json manager from string path and cache it on a php file named cache
$manager = ConfigManager::getInstance('config.json', 'cache.php');


//get the json manager from string path
$dataDriver = new SimpleManager();
$dataDriver->set('dataPath', 'config.json');
$manager = ConfigManager::getInstance($dataDriver);

//get the json manager from string path and cache it on a php file named cache
$dataDriver = new SimpleManager();
$dataDriver->set('dataPath', 'config.json');
$cacheDriver = new SimpleManager();
$cacheDriver->set('dataPath', 'cache.php');
$manager = ConfigManager::getInstance($dataDriver, $cacheDriver);

//driver.json contains the key 'dataPath' with value 'config.xml' and others key as manual explain.
//so an xml manager is returned   
$dataDriver = new SimpleManager();
$dataDriver->set('driverPath', 'driver.json');
$manager = ConfigManager::getInstance($dataDriver);

?>