<?php

namespace ConfigManager\Interfaces;

interface Manager
{
    public function set($key, $value = false);
    public function add($key, $value = false);
    public function replace($key, $value = false);
    public function get($key);
    public function asArray();
    public function exist($key);
    public function delete($key);
    public function merge(Manager $from);
    public function getId();
}