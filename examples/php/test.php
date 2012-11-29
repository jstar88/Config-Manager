<?php

include ('../../ConfigManager.php');
$config=ConfigManager::getInstance('config.php');

$config->set('ciao','ciao');

echo $config->get('ciao');
