<?php
namespace ConfigManager\Modules\Json\Exceptions;

class JsonException extends \Exception
{
    public function __construct($error)
    {
        parent::__construct("Error managing json: $error", 0);
    }
}