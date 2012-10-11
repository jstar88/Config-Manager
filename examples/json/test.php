<?php
	include ('../../ConfigManager.php');
$config = ConfigManager::getInstance('config.json','cache.config.php');
$foods=$config->get('foods');
var_export($foods);
?>