<?php
	include ('../../ConfigManager.php');
$config = ConfigManager::getInstance('config.json','cache.php');
$foods=$config->get('foods');
var_export($foods);

/*
$source=new ConfigFile();
$source->dataPath='config.json';

$cache=new ConfigFile();
$cache->dataPath='cache.php';
ConfigManager::getInstance($source,$cache);
*/
?>