<?php

include ('../../ConfigManager.php');
$config = ConfigManager::getInstance('config.pdo');
$config->showDebug();

#Alternative way
/**
 * require('../../SimpleManager.php');
 * $source=new SimpleManager();
 * $source->set('dataPath','config.pdo');
 * $source->set('service','php');

 * $cache=new SimpleManager();
 * $source->set('dataPath','cache.php');
 * ConfigManager::getInstance($source,$cache);
 */
?>