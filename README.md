***********************
******* didlie ********
(c) Isaac Jacobs - 2013
***********************
****** Lic. MIT *******
***********************

# Secure PHP Application, a framework.

1) Fork this as a template

2) go to the htaccess and phpini files in ./readonly and edit them to fit your website, redirect ideas have been commented in, and all file extension handling and http -to- https redirects should be defined

3) your application code will be in the ./app folder and should be accessed via an index.php file in this folder

4) the text file 'registry' must contain a complete list of every file in your website root

5) This function in the 'request' class must be hard coded with all allowed GET and POST keys and allowed types and other restrictions you want to make on data sent from client to server.

```
public function filter_request(){
        //filter SUPERGLOBALS
        //$GLOBALS
        //$_SERVER
        //$_REQUEST
        //$_POST
        //$_GET
        //$_FILES
        //$_ENV
        //$_COOKIE
        //$_SESSION
    }
```
  6) This function in the ./root/index.php must be edited when your app is deployed on a public server.
  
```
  function set_error_reporting(){
    error_reporting(E_ALL);//comment out after deployment
    ini_set('display_errors', 1);//comment out after deployment
}
```

### This template works by handling every request to the server, rewriting .htaccess and php.ini on every instance of a request, filtering all Superglobals sent from the client, validating all files in the website root by referencing the ./root/registry to see what files you want to be there, and finally ends the php script execution (after your app code was executed) redundently to stop the multiple-request crawler attack.
