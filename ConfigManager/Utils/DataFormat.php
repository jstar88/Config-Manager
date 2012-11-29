<?php

namespace ConfigManager\Utils;

/**
 * DataFormat 
 * @package Config-Manager 
 * @author Covolo Nicola
 * @copyright 2012
 * @version 4.0.6
 * @access public
 */
class DataFormat
{
    static $link = false;
    
    
    /**
     * DataFormat::unserialize() 
     * @param mixed $string
     * @return
     */
    public static function unserialize($string)
    {
        if (!self::$link)
        {
            error_reporting(E_ALL ^ E_NOTICE);
            self::$link = true;
        }
        $var = unserialize($string);
        return ($var === false) ? $string : $var;
    }
    
    
    /**
     * DataFormat::serialize() 
     * @param mixed $value
     * @return
     */
    public static function serialize($value)
    {
        if (is_array($value) || is_object($value))
        {
            return serialize($value);
        }
        return $value;
    }
}
