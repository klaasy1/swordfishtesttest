<?php
/**
 * Description of Router
 *
 * @author Klaas
 */
class Router extends Main {
    function __construct() {
        $this->setRoute();
        $this->route();
    }
    
    function setRoute() {
        //Set Module to Load
        if (!empty($_GET['module'])) {
            $this->module = $_GET['module'];
        } else {
            $this->module = 'Index';
        }
        
        //Get Paramaters ($_GET['params']) set with the .htaccess file
        if (!empty($_GET['params'])) {
            $this->params = explode('/',$_GET['params']);
        }
        
        /*
         * Set the function to be called (The first value of $this->params)
         * unset the first value of params so that the function name is not one of the paramaters sent to the function
         */
        if (isset($this->params[1])) {
            $this->function = $this->params[1];
            unset($this->params[1]);
        }
    }
    
    function route() {
        
        //Include the file required for the module
        try {
            include('Modules'.DIRECTORY_SEPARATOR.$this->module.DIRECTORY_SEPARATOR.$this->module.'.php');
        } catch (Exception $e) {
            parent::setException($e->getMessage());
        }
        
        //Instantiate the module (Class)
        $object = new $this->module;
        
        if (isset($this->function)) {
        //Instantiate a new reflection method (this will be able to call a dynamic method inside a dynamic object with dynamic paramaters)
        $obj = new ReflectionMethod($this->module,$this->function);
        $obj->invokeArgs($object,$this->params);
        } else {
            $object->render();
        }
    }
    
    function render() {
        
    }
}