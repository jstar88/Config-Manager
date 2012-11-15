<?php

include ('../../ConfigManager.php');
$config=ConfigManager::getInstance('config.php','cache.php');

$config->set('ciao','ciao');

echo $config->get('ciao');
