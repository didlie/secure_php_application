<?php

set_error_reporting();

require_once("../interfaces/I_request.php");
require_once("../interfaces/I_registry.php");
require_once("../classes/request.php");
require_once("../classes/registry.php");

$request = new request();
//this is a request to this website
$request->write_php_ini();
$request->write_htaccess();
$request->filter_request();
$request->application_root();//includes registry details

$register = new registry();//everything in the root needs to be in the registry
$register->clean_root();

//back to the request
$request->application_root();
$request->start_application();
$request->exit_application();

//************functions*************//

function set_error_reporting(){
    error_reporting(E_ALL);//comment out after deployment
    ini_set('display_errors', 1);//comment out after deployment
}

 ?>
