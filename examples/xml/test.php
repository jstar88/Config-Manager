<?php

include ('../../ConfigManager.php');
$config=ConfigManager::getInstance('config.xml');
    $config->add('users_amount',4);
    echo $config->get('users_amount');


?>