<?php
namespace ConfigManager\Exceptions;

class ItemAlreadyExistException extends \Exception
{
    public function __construct($id)
    {
        parent::__construct("Error: Item with id '$id' already exists.", 0);
    }
}