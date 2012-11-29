<?php
namespace ConfigManager\Modules\System\File\Exceptions;

class FileNotWritableException extends \Exception
{
    public function __construct($path)
    {
        parent::__construct("Error: $path is not writable", 0);
    }
}