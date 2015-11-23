<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Index
 *
 * @author klaas
 */
class Index extends Main {
    function __construct() {
        
    }
    
    function render() {
        
        //Show login page
        include(MODULE_PATH.'Issue'.DIRECTORY_SEPARATOR.'Issue.php');
        $content = new Issue();
            
        include(MODULE_PATH.'Index'.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'index.phtml');
        
    }
}
