<?php

namespace ConfigManager\Interfaces;

interface Decorator
{
     /**
      * __call()
      * as php manual
      * @param string $method_name
      * @param array $args
      * @return
      */
     function __call($method_name, $args);
}