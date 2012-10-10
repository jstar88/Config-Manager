<?php

class DataFormat
{
    static $link=false;
    public static function unserialize($string)
    {
        if(!self::$link)
        {
            error_reporting(E_ALL ^ E_NOTICE);
            self::$link = true;
        }
        $var = unserialize($string);
        return ($var === false) ? $string : $var;
    }
    public static function serialize($value)
    {
        if (is_array($value) || is_object($value))
        {
            return serialize($value);
        }
        return $value;
    }
}

?>