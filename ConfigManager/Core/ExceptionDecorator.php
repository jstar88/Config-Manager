<?php

namespace \ConfigManager\Core;
use \ConfigManager\Interfaces\Decorator as Decorator;
use \ConfigManager\Interfaces\Manager as Manager;

class ExceptionDecorator implements Decorator
{
    protected $manager;
    private $exception;

    function __construct(Manager $manager, string $exception)
    {
        $this->manager = $manager;
        $this->exception = $exception;
    }

    function __call($method_name, $args)
    {
        $return = null;
        try
        {
            $return = call_user_func_array(array($this->manager, $method_name), $args);
        }
        catch (exception $e)
        {
            $exception = $this->exception;
            throw new $exception($e->getMessage());
        }
        return $return;
    }
}
