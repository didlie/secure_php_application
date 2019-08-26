<?php

//this handles php handling of every request, call to the server

interface I_request{

    public function write_php_ini();//recreate the php.ini file

    public function write_htaccess();//recreate the .htaccess file

    public function filter_request();//filter SUPERGLOBALS
    //$GLOBALS $_SERVER $_REQUEST $_POST $_GET $_FILES $_ENV $_COOKIE $_SESSION

    public function application_root();//set the application directory, not in URL root

    public function start_application();//include the index file for the site application

    public function exit_application();//end application execution with a recursive exit() function, like yo_exit

}


 ?>
