<?php
namespace ConfigManager\Exceptions;

class ItemNotUniqueException extends \Exception
{
    public function __construct($id)
    {
        parent::__construct("Error: Item with id '$id' is not unique.", 0);
    }
}