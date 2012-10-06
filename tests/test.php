<?php

include ('../ConfigManager.php');
$config=ConfigManager::getIstance('config.xml', 'cache.config.php');
$config->add('users_amount2',2);
echo $config->get('users_amount2');
print_r(ConfigManager::$parseCount);

?>