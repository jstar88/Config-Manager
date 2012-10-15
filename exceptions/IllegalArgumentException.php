<?php

class IllegalArgumentException extends Exception
{
    public function __construct($argument,$form)
    {
        parent::__construct("Error: argument $argument must be on the form '$form'",0);
    }
}

?>