<?php
namespace ConfigManager\Modules\Plugins\Cookie\Exceptions;

class CookieException extends \Exception
{
    public function __construct($error)
    {
        parent::__construct("Error managing cookie: $error", 0);
    }
}