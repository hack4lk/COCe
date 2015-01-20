<?php
/*
 * Abstract class to read and write data to disk
 */

class FileManager{
    
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
}

?>