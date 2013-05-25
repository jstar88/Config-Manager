<?php

include ('../../ConfigManager.php');
$config = ConfigManager::getInstance('config.ini');
//if(!$config->exist('string')) $config->add('string','hello world');
echo $config->get('string');
?>