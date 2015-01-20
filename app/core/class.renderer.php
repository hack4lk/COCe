<?php
/*
 * Simple render class to output data back to front-end
 */
 
class Renderer{
    
   public function __construct(){
       //do nothing for now...
   }
   
   public function output($template = "", $data = ""){
       if($template === ""){
           $this->showError(1);
       }
       
       $file = APP_PATH .'/views/' . $template . '.php';
       if(file_exists($file)){
           require_once($file);
       }else{
           $err = "View file '" . $template . "' not found!";
           $this->showError(2, $err);
       }      
   }
   
   private function showError($numb, $override = ""){
       $msg = "";
       if($override != ""){
           $msg = $override;
       }
       
       switch ($numb) {
           case 1:
               $msg = 'View file name not passed';
               break;
           case 2:
               $msg = $msg; //this is a message with override
               break;
           default:
               $msg = "There was a problem loading the view.";
               break;
       }
       
       throw new Exception($msg, 1);
   }
}

?>