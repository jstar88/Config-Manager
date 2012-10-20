<?php
namespace ConfigManager\Exceptions;
class XmlException extends \Exception
{
    public function __construct($error)
    {
        parent::__construct("Error managing xml: $error",0);
    }
}

?>