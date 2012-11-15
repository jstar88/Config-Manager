<?php
	include ('../../ConfigManager.php');
$config = ConfigManager::getInstance('config.cookie');
$config->add('ciao','mondo');
$a=$config->asArray();
print_r($a);
?>