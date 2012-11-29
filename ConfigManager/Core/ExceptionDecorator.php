<?php

namespace ConfigManager\Core;
use \ConfigManager\Interfaces\Decorator as Decorator;
use \ConfigManager\Interfaces\Manager as Manager;

/**
 * ExceptionDecorator
 * Class used to better display exceptions: store the manager in a field and then intercept all function's call to it, in case of exceptions show them and die. 
 * 
 * @package Config-Manager
 * @author Covolo Nicola
 * @copyright 2012
 * @version 4.0.6
 * @access public
 */
class ExceptionDecorator implements Decorator
{
    protected $manager;


    /**
     * ExceptionDecorator::__construct()
     * Store the manager in a class field
     * @param mixed $manager
     * @return
     */
    function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }


    /**
     * ExceptionDecorator::__call()
     * When Manager's functions are called, this function intercept and call them
     * @param string $method_name
     * @param array $args
     * @return mixed
     */
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
    
    
    /**
     * ExceptionDecorator::showException()
     * Html format an exception
     * @param Exception $e
     * @return string
     */
    private function showException(exception $e)
    {
        $eol= PHP_EOL;
        $message = $exception->getMessage();
        $code = $exception->getCode();
        $file = $exception->getFile();
        $line = $exception->getLine();
        $trace = $exception->getTraceAsString();
        $date = date('M d, Y h:iA');
        $log_message = "<h3>Exception information:</h3>
         <p>
            <strong>Date:</strong> $date
         </p>
         $eol
         <p>
            <strong>Message:</strong> $message
         </p>
         $eol
         <p>
            <strong>Code:</strong> $code
         </p>
         $eol
         <p>
            <strong>File:</strong> $file
         </p>
         $eol
         <p>
            <strong>Line:</strong> $line
         </p>
         $eol
         <h3>Stack trace:</h3>
         <pre>$trace
         </pre>
         <br />
         <hr /><br /><br />";
        return $log_message;
    }
}
