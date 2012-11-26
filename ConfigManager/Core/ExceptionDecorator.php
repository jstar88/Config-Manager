<?php

namespace \ConfigManager\Core;
use \ConfigManager\Interfaces\Decorator as Decorator;
use \ConfigManager\Interfaces\Manager as Manager;

class ExceptionDecorator implements Decorator
{
    protected $manager;

    function __construct(Manager $manager, string $exception)
    {
        $this->manager = $manager;
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
            die($this->showException($e));
        }
        return $return;
    }
    private function showException(exception $e)
    {
        $message = $exception->getMessage();
        $code = $exception->getCode();
        $file = $exception->getFile();
        $line = $exception->getLine();
        $trace = $exception->getTraceAsString();
        $date = date('M d, Y h:iA');
        $log_message = "<h3>Exception information:</h3>
         <p>
            <strong>Date:</strong> {$date}
         </p>
         <p>
            <strong>Message:</strong> {$message}
         </p>
         <p>
            <strong>Code:</strong> {$code}
         </p>
         <p>
            <strong>File:</strong> {$file}
         </p>
         <p>
            <strong>Line:</strong> {$line}
         </p>
         <h3>Stack trace:</h3>
         <pre>{$trace}
         </pre>
         <br />
         <hr /><br /><br />";
        return $log_message;
    }
}
