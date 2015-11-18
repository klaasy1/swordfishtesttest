<?php
// Report all errors
error_reporting(E_ERROR);

// Same as error_reporting(E_ALL);
ini_set("error_reporting",E_ERROR);
ini_set('date.timezone','Africa/Johannesburg');

function modulePath($module) {
    return str_replace('{module}',$module,HTTP_MODULE_PATH);
}

//Get the http base URL for CSS and JS Includes
function baseUrl($atRoot=FALSE, $atCore=FALSE, $parse=FALSE){
    if (isset($_SERVER['HTTP_HOST'])) {
        $http = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
        $hostname = $_SERVER['HTTP_HOST'];
        $dir =  str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

        $core = preg_split('@/@', str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath(dirname(__FILE__))), NULL, PREG_SPLIT_NO_EMPTY);
        $core = $core[0];

        $tmplt = $atRoot ? ($atCore ? "%s://%s/%s/" : "%s://%s/") : ($atCore ? "%s://%s/%s/" : "%s://%s%s");
        $end = $atRoot ? ($atCore ? $core : $hostname) : ($atCore ? $core : $dir);
        $base_url = sprintf( $tmplt, $http, $hostname, $end );
    }
    else $base_url = 'http://localhost/';

    if ($parse) {
        $base_url = parse_url($base_url);
        if (isset($base_url['path'])) if ($base_url['path'] == '/') $base_url['path'] = '';
    }
    return $base_url;
}

$requestUri = baseUrl();

//Define some constants
define('BASE_PATH',getcwd().DIRECTORY_SEPARATOR);
define('CLASS_PATH',BASE_PATH.'Classes'.DIRECTORY_SEPARATOR);
define('MODULE_PATH',BASE_PATH.'Modules'.DIRECTORY_SEPARATOR);
define('HTTP_MODULE_PATH',$requestUri.'/Modules/{module}/');
define('LIBRARY_PATH',$requestUri.'Lib');
define('LIBRARYPHP_PATH',BASE_PATH.'Lib');
define('OWNER','WorkAtSwordfish');
define('REPO','GitIntegration');
define('BASE_HTTP_PATH',$requestUri);