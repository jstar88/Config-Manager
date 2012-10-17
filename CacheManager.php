<?php

class CacheManager implements ExtensionManager
{
    private $primitive;
    private $cache;
    public function __construct(ExtensionManager $primitive, ExtensionManager $cache)
    {
        $this->primitive = $primitive;
        $this->cache = $cache;
        if ($this->cache->asArray() === null)
            $this->cache->add($this->primitive->asArray());
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

}

?>