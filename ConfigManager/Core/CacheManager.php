<?php

namespace ConfigManager\Core;

use \ConfigManager\Interfaces\Manager as Manager;

class CacheManager implements Manager
{
    private $primitive;
    private $cache;
    public function __construct(Manager $primitive, Manager $cache)
    {
        $this->primitive = $primitive;
        $this->cache = $cache;
        $content = $this->cache->asArray();
        if (empty($content))
        {
            $this->cache->add($this->primitive->asArray());
        }
    }
    public function set($key, $value = false)
    {
        $this->cache->set($key, $value);
        $this->primitive->set($key, $value);
    }
    public function add($key, $value = false)
    {
        $this->cache->add($key, $value);
        $this->primitive->add($key, $value);
    }
    public function replace($key, $value = false)
    {
        $this->cache->replace($key, $value);
        $this->primitive->replace($key, $value);
    }
    public function merge(Manager $manager)
    {
        $this->cache->merge($manager);
        $this->primitive->merge($manager);
    }
    public function get($key)
    {
        return $this->cache->get($key);
    }
    public function asArray()
    {
        return $this->cache->asArray();
    }
    public function exist($key)
    {
        return $this->cache->exist($key);
    }
    public function delete($key)
    {
        $this->cache->delete($key);
        $this->primitive->delete($key);
    }
    public function getId()
    {
        return $this->cache->getId();
    }

}
