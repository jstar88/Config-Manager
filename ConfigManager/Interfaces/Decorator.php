<?php

namespace ConfigManager\Interfaces;

interface Decorator
{
     function __call($method_name, $args);
}