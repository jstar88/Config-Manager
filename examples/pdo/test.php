<?php

include ('../../ConfigManager.php');
$config = ConfigManager::getInstance('config.pdo');


$config->showDebug();

/* Alternative way
$source=new ConfigFile();
$source->driverPath='pdo.php';

$cache=new ConfigFile();
$cache->dataPath='cache.php';
ConfigManager::getInstance($source,$cache));
*/
?>