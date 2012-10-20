<?php
	include ('../../ConfigManager.php');
$config = ConfigManager::getInstance('config.json','cache.php');
if(!$config->exist('foods')) $config->add('foods',array('pizza','egg'));
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