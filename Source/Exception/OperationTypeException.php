<?php

namespace Source\Exception;

use Exception;

class OperationTypeException extends Exception
{
    public function __construct($message, $code = 3, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
    
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}