<?php
/*
 * Main error class for the application
 */

class Error{
    
    public function __construct(){
        //nothing for now....
    }
    
    public function throwError($msg){
        throw new Exception($msg, 1);
        exit();
    }
}
?>