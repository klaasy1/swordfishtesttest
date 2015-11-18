<?php
/**
 * $this is an abstract class to define the module that will be loaded, function that must be called and the parameters to be sent.
 * All Classes extending this class will have a render() method
 *
 * @author Klaas
 */
abstract class Main {
    public $module = null;
    public $function = null;
    public $params = array();
    public $exceptions = array();

    public function setException($exception) {
        $this->exceptions = $exception;
    }
    
    public function foundException() {
        if (count($this->exceptions) > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    public function getExceptions() {
        return $this->exceptions;
    }
    
    abstract function render();
}