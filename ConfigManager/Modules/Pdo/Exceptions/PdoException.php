<?php
namespace ConfigManager\Modules\Pdo\Exceptions;

class PdoException extends \Exception
{
    public function __construct($error)
    {
        parent::__construct("Error managing PDO: $error", 0);
    }
}