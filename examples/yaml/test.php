<?php

include ('../../ConfigManager.php');
$config=ConfigManager::getInstance('config.yaml', 'cache.config.php');

$wines=$config->get('wines');
var_export($wines);
?>