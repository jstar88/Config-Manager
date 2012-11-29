<?php

namespace ConfigManager\Core;

use \ConfigManager\Interfaces\Manager as Manager;

/**
 * CacheManager
 * Class used to simulate a cache
 * @package Config-Manager
 * @author Covolo Nicola
 * @copyright 2012
 * @version 4.0.6
 * @access public
 */
class CacheManager implements Manager
{
    private $primitive;
    private $cache;
    
    
    /**
     * CacheManager::__construct()
     * @param Manager $primitive
     * @param Manager $cache
     * @return CacheManager
     */
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
    
    
    /**
     * CacheManager::set() 
     * @param mixed $key
     * @param bool $value
     * @return null
     */
    public function set($key, $value = false)
    {
        $this->cache->set($key, $value);
        $this->primitive->set($key, $value);
    }
    
    
    /**
     * CacheManager::add()
     * @param mixed $key
     * @param bool $value
     * @return null
     */
    public function add($key, $value = false)
    {
        $this->cache->add($key, $value);
        $this->primitive->add($key, $value);
    }
    
    
    /**
     * CacheManager::replace()
     * @param mixed $key
     * @param bool $value
     * @return null
     */
    public function replace($key, $value = false)
    {
        $this->cache->replace($key, $value);
        $this->primitive->replace($key, $value);
    }
    
    
    /**
     * CacheManager::merge() 
     * @param mixed $manager
     * @return null
     */
    public function merge(Manager $manager)
    {
        $this->cache->merge($manager);
        $this->primitive->merge($manager);
    }
    
    
    /**
     * CacheManager::get()
     * @param mixed $key
     * @return mixed
     */
    public function get($key)
    {
        return $this->cache->get($key);
    }
    
    
    /**
     * CacheManager::asArray() 
     * @return array
     */
    public function asArray()
    {
        return $this->cache->asArray();
    }
    
    
    /**
     * CacheManager::exist() 
     * @param mixed $key
     * @return boolean
     */
    public function exist($key)
    {
        return $this->cache->exist($key);
    }
    
    
    /**
     * CacheManager::delete() 
     * @param mixed $key
     * @return null
     */
    public function delete($key)
    {
        $this->cache->delete($key);
        $this->primitive->delete($key);
    }
    
    
    /**
     * CacheManager::getId() 
     * @return mixed
     */
    public function getId()
    {
        return $this->cache->getId();
    }

}
