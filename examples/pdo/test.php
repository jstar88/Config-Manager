<?php

include ('../../ConfigManager.php');
$config = ConfigManager::getInstance('config.pdo');
if(!$config->exist('id'))
{
    $config->add('id',134);
}
$config->set(array('version'=>6,'id'=>135));

var_export($config->asArray()); //cached

/* Alternative way
$source=new ConfigFile();
$source->driverPath='pdo.php';

$cache=new ConfigFile();
$cache->dataPath='cache.php';
ConfigManager::getInstance($source,$cache));
*/
?>