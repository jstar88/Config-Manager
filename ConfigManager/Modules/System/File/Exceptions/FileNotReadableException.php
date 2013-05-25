<?php
namespace ConfigManager\Modules\System\File\Exceptions;

class FileNotReadableException extends \Exception
{
    public function __construct($path)
    {
        parent::__construct("Error: $path is not readble", 0);
    }
}