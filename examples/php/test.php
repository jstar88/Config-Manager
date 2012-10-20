<?php

include ('../../ConfigManager.php');
$config=ConfigManager::getInstance('config.php','cache.php');

  if(!$config->exist('foods'))
      $config->add('foods',array('pizza','spaghetti'));
  $config->set('foods','egg');
  $foods= $config->get('foods');
  var_export($foods);


?>