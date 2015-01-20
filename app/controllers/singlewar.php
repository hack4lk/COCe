<?php
//show results for any single war...
class SingleWar implements CONTROLLER{
   
    private $dataManager;
    private $view;
    
    public function __construct(){
       $this->dataManager = new DataManager();
       $this->view = new Renderer();
    }
    
    public function index(){
        if(isset($_GET['date'])){
            $date = $_GET['date'];
            if($date != ""){
                //use datamanager and output to output data
                $result = $this->dataManager->getDataForSpecificWar($date);
                 
                //load the data into view    
                try{
                    $this->view->output('single-war', $result);
                }catch(Exception $e){
                    echo $e->getMessage();
                    exit();
                }
            }else{
                echo "ERROR: Date is empty";
            }
        }else{
            echo "ERROR: Date not present in request";
        }
    }
    
    public function saveWarData(){
        echo "saving data...";
    }
}
?>