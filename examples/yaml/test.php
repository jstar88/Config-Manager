<?php

include ('../../ConfigManager.php');
$config=ConfigManager::getInstance('config.yaml','cache.php');

if(!$config->exist('wines'))$wines=$config->add('wines','cabernet');
echo $config->get('wines');
?>