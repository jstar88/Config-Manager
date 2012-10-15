<?php

include ('../../ConfigManager.php');
$config=ConfigManager::getInstance('config.xml', 'cache.php');
if(!$config->exist('users_amount'))
    $config->add('users_amount',4);
$config->set('users_amount',2);
echo $config->get('users_amount');

?>