<?php

include ('../../ConfigManager.php');
$config = ConfigManager::getInstance('config.ini','cache.php');
if(!$config->exist('string')) $config->add('string','hello world');
echo $config->get('string');
?>