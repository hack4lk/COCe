<?php
//show results for any single war...
class SingleWar extends CONTROLLER{
   
    private $dataManager;
    private $view;
    private $url;
    
    public function __construct(){
       $this->dataManager = new DataManager();
       $this->view = new Renderer();
       $this->url = new Url();
    }
    
    public function index(){
        if(isset($_GET['date'])){
            $date = $_GET['date'];
            if($date != ""){
                //use datamanager and output to output data
                $result = $this->dataManager->getDataForSpecificWar($date);
                if(!$result){
                    $this->throwError("war file not found or is empty");
                }
                 
                //try to load the data into view    
                try{
                    $this->view->output('single-war', $result);
                }catch(Exception $e){
                    $this->throwError($e->getMessage());
                }
            }else{
                $this->throwError("ERROR: Date is empty");
            }
        }else{
            $this->throwError("ERROR: Date not present in request");
        }
    }
    
    public function saveWarData(){
        $data = "";
        
        //$this->url->redirect('app/?task=singlewar&date=1-20-2015');
        
        if(!isset($_FILES['warDataFile']) || $_FILES['warDataFile']["name"] == ""){
            $this->throwError("No file passed");
        }else{
            $tmpFileData = file_get_contents($_FILES["warDataFile"]["tmp_name"]);
            
            $verifiedData = $this->dataManager->checkAndFormatData($tmpFileData);
            
            if(!$verifiedData){
                $this->throwError('Data format incompatible.');
            }else{
                if($this->dataManager->setDataForSpecificWar($verifiedData)){
                    //if everything was ok, update the main
                    //stats file
                    if($this->dataManager->setStatsForSpecificWar($verifiedData)){
                        $this->url->redirect("index.php?update=success");
                    }else{
                        $this->throwError("Could not update war data.");
                    }
                }else{
                    $this->throwError("Could not save data file. Please check folder permissions.");
                }
            }
        }
        
        
        $result = "";
        //$result = $this->dataManager->setDataForSpecificWar($data);
        
        //no need to load anything into view here. we return error or true  
        if(!$result){
            $result = "Error saving war data. Please check your file and try again.";
        }     
    }
}
?>