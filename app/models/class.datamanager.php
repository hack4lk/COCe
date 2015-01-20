<?php
/*
 * The DataManager class in designed to write and read data
 * from the filesystem as well as parse data
 */
require_once(APP_PATH .'/core/class.filemanager.php');
 
class DataManager extends FileManager{
    
    public function __construct(){
        //do nothing for now
    }
    
    public function getDataForSpecificWar($date){
        $warData = "";    
        $warFile = $date . ".json";
        if(!($warData = $this->loadDataFile($warFile))){
            echo "war file not found or is empty";
        }else{
            //this means the file exists
            echo $warData;
        }
    }
    
    public function setDataForSpecificWar($rawData = 0){
        
    }
}

?>