<?php
namespace ConfigManager\Modules\System\File\Exceptions;

class FileNotExistException extends \Exception
{
    public function __construct($path)
    {
        parent::__construct("Error: $path doesn't exist", 0);
    }
}