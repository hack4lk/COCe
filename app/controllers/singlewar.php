<?php
//show results for any single war...
class SingleWar extends CONTROLLER{
   
    private $dataManager;
    private $view;
    private $url;
    private $warData;
    
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
                if(isset($_GET['showstats']) && $_GET['showstats'] == 'true' ){
                    $fullName = $date . "_stats";
                    $result = $this->dataManager->getDataForSpecificWar($fullName);
                }else{
                    $result = $this->dataManager->getDataForSpecificWar($date);    
                }
                
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
        //check if api key is passed and is valid otherwise throw error
        if(!isset($_POST['appkey']) || $_POST['appkey'] == ""){
            $this->throwError("Upload Failed. Application key not provided.");
        }else if($_POST['appkey'] != AUTH_TOKEN){
            $this->throwError("Application key is not valid!");
        }
        
        //check to see if we're uploading a war data file or passing in form information...
        if(isset($_GET['formData']) && $_GET['formData'] == 'true'){
            //this means we're passing in form information instead of uploading war log file
            if($this->verifyWarData("form")){
                $this->url->redirect("index.php?update=success");
            }
        }else{
            $file = pathinfo($_FILES["warDataFile"]["name"]);
            $fileext = strtolower($file['extension']);   
    
            if(!isset($_FILES['warDataFile']) || $_FILES['warDataFile']["name"] == "" || $fileext != 'csv'){
                $this->throwError("No file or wrong file format passed");
            }else{
                $this->warData = file_get_contents($_FILES["warDataFile"]["tmp_name"]);
                
                if($this->verifyWarData("file")){
                    $this->url->redirect("index.php?update=success");
                }
            }
        }
    }

    private function verifyWarData($dataSource){
        $verifiedData = null;
        
        if($dataSource == "file"){
            $verifiedData = $this->dataManager->checkAndFormatDataFromFile($this->warData);
        }else if($dataSource == "form"){
            $verifiedData = $this->dataManager->checkAndFormatDataFromForm($this->warData);
        }
            
        if(!$verifiedData){
            $this->throwError('Data format incompatible.');
        }else{
            if($this->dataManager->setDataForSpecificWar($verifiedData)){
                //if everything was ok, update the main
                //stats file
                if($this->dataManager->setStatsForSpecificWar($verifiedData)){
                    return true;
                }else{
                    $this->throwError("Could not update war data.");
                }
            }else{
                $this->throwError("Could not save data file. Please check folder permissions.");
            }
        }
    }
}
?>