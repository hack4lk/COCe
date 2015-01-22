<?php
/*
 * Router class to process requests sent to backend API
 */

class Router extends Error{
    
    private $fileName = "";
    private $methodName = "";
    
    public function __construct(){
        //nothing for now...
    }
    
    //router function to route the proper requests
    public function route($taskName, $method = ""){
        $this->fileName = strtolower(htmlspecialchars($taskName, ENT_NOQUOTES));
        $this->methodName = strtolower(htmlspecialchars($method, ENT_NOQUOTES));
        
        //first we make sure the file is on the server and we require it
        if(file_exists(APP_PATH . '/controllers/' . $this->fileName . '.php')){
            require_once(APP_PATH . '/controllers/' . $this->fileName. '.php');
            
            //next we instantiate the controller class from that file
            //and call the index function
            $className = $this->fileName;
            $obj = new $className();
            
            //if a method of the class is passed, call it here...
            if($this->methodName != ""){
                if(method_exists($obj, $this->methodName)){
                    $finalMethod = $this->methodName;
                    $obj->$finalMethod();
                }else{
                    $msg = "Method '" . $this->methodName . "' of Class '" . $this->fileName . "' not found.";
                    $this->throwError($msg);
                }
            }else{
                $obj->index();
            }
              
        }else{
           $this->throwError('The specified application path does not exist.');
           
        }
    }
}

?>