<?php
namespace ConfigManager\Utils;

use \ConfigManager\Exceptions\FileNotExistException as FileNotExistException;
use \ConfigManager\Exceptions\FileNotReadableException as FileNotReadableException;
use \ConfigManager\Exceptions\FileNotWritableException as FileNotWritableException;

define(__NAMESPACE__.'\WAIT_FOR',200000);
define(__NAMESPACE__.'\PERMISSIONS', 0664);
class SafeIO
{
    public static function open($path)
    {
        if (!file_exists($path))
        {  
            throw new FileNotExistException($path);
        }
        if (!is_readable($path))
        {
            throw new FileNotReadableException($path);
        }
        $fo = fopen($path, 'r');
        flock($fo, LOCK_SH);
        $cts = file_get_contents($path);
        flock($fo, LOCK_UN);
        fclose($fo);
        chmod($path,PERMISSIONS);
        return $cts;
    }
    public static function save($content,$path)
    {
        if (file_exists($path) && !is_writable($path))
        {
            throw new FileNotWritableException($path);
        }
        $fp = fopen($path, "w");
        if (flock($fp, LOCK_EX))
        {
            fwrite($fp, $content);
            fflush($fp); // flush output before releasing the lock
            flock($fp, LOCK_UN); // release the lock
            fclose($fp);
        }
        //if is not possible then wait for 0.2 seconds and then retray
        else
        {
            fclose($fp);
            usleep(WAIT_FOR);
            $this->save($content);
        }
        chmod($path,PERMISSIONS);
    }
}

?>