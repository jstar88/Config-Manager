<?php

namespace ConfigManager\Interfaces;

interface Manager
{
    /**
     * set()
     * store an value that can be an object,array or basic type in the corrispective existing key, or store all variables inside an array or object passed like key<br>
     * if key already exist, value will be overrided.
     * if key don't exist, new key-value will be created
     * @param mixed $key
     * @param mixed $value
     * @return null
     */
    public function set($key, $value = false);
    
    
    /**
     * add()
     * store an value that can be an object,array or basic type in the corrispective existing key, or store all variables inside an array or object passed like key<br>
     * if key already exist, exception will be thrown.
     * if key don't exist, new key-value will be created  
     * @param mixed $key
     * @param mixed $value
     * @return null
     */
    public function add($key, $value = false);
    
    
    /**
     * replace()
     * store an value that can be an object,array or basic type in the corrispective existing key, or store all variables inside an array or object passed like key<br>
     * if key already exist, value will be replaced.
     * if key don't exist, exception will be thrown
     * @param mixed $key
     * @param mixed $value
     * @return null
     */
    public function replace($key, $value = false);
    
    
    /**
     * get()
     * return a value stored with the key passed as param
     * @param mixed $key
     * @return mixed
     */
    public function get($key);
    
    
    /**
     * asArray()
     * return an associative array of key->value of all data
     * @return array
     */
    public function asArray();
    
    
    /**
     * exist()
     * return true if the key already exist
     * @param mixed $key
     * @return boolean
     */
    public function exist($key);
    
    
    /**
     * delete()
     * delete a value stored with the key passed as param 
     * @param mixed $key
     * @return null
     */
    public function delete($key);
    
    
    /**
     * merge()
     * Merge all data from the argument into the current manager
     * @param Manager $from
     * @return null
     */
    public function merge(Manager $from);
    
    
    /**
     * getId()
     * return the Id
     * @return mixed
     */
    public function getId();
}