<?php

namespace ConfigManager\Modules\System\File\Exceptions;

class FileViolationException extends \Exception
{
    public function __construct($path)
    {
        parent::__construct("Error: configuration file '$path' was modified bye external program.", 0);
    }
}