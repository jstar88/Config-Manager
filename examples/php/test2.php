<?php

include ('../../ConfigManager.php');
$source=new ConfigFile();
$source->dataPath='config.php';
$cache=new ConfigFile();
$cache->dataPath='test2_cache.php';
$config= ConfigManager::getInstance($source,$cache);
echo $config->get('foods');
?>