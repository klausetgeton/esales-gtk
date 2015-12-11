<?php

class MyException extends Exception
{
    public function __construct($message, $code = 0, Exception $previous = null) {
     
     	LogException::log($message);  	

        parent::__construct($message, $code, $previous);
    }
}