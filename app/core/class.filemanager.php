<?php
/*
 * Abstract class to read and write data to disk
 */

class FileManager extends Error{
    
    private $fileData;
    
    public function __construct(){
        //nothing for now...
    }
    
    protected function loadDataFile($fileName){
        $fullPath = DATA_PATH . '/' . $fileName;
        if(!file_exists($fullPath)){
            return false;
        }else{
            $fileData = file_get_contents($fullPath);
            return $fileData;
        }
    }
    
    protected function saveDataFile($fileName, $data){
        //this function will write and overwrite files
        //without complaining so be careful because it's a lazy writer
        //it will also create files if they don't exist
        if(file_put_contents($fileName, $data)){
            return true;
        }else{
            return false;
        }
        
    }
    
    protected function fileExists($fileName){
        if(file_exists($fileName)){
            return true;
        }else{
            return false;
        }
    }
}

?>