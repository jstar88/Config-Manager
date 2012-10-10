<?php

include ('../../ConfigManager.php');
$config = ConfigManager::getInstance('config.mysql','cache.config.php');


if($config->exist('wines')) //cached
    $config->delete('wines'); //not cached
$config->add('wines',array('merlot','cabernet')); //not cached

$config->get('wines'); //cached

?>