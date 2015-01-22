<?php
/*
 * URL helper class for things like redirects and other path-based functionality
 */

class Url{
    
    private $redirectURL = "";
    
    public function __construct(){
        //do nohting for now
    }
    
    public function redirect($path){
        $this->redirectURL = APP_URL . '/' . htmlspecialchars($path);
        header("Location: {$this->redirectURL}");
    }
}
?>