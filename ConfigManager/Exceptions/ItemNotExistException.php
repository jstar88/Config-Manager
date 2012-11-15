<?php
namespace ConfigManager\Exceptions;

class ItemNotExistException extends \Exception
{
    public function __construct($id)
    {
        parent::__construct("Error: Item with id '$id' does not exists.", 0);
    }
}