<?php

//all registry requirements integrated into an interface to ensure security in the root directory

interface I_registry{

    public static function create_registry();//creates the registry based on the root directory's existing files
    //this is a convenience function for developers to avoid handwriting the "registry" text file

    public static function clean_root();//this function should recursively check all files in the root,
    //in any folders that might exist in the root and verify each file's legitimacy based on the "registry" text file

}

?>
