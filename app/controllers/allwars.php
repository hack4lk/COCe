<?php
//show stats for all wars

class AllWars extends CONTROLLER{
    
    private $dataManager;
    private $view;
    
    public function __construct(){
       $this->dataManager = new DataManager();
       $this->view = new Renderer();
    }
    
    public function index(){
        $result = $this->dataManager->getAllWarStats();
        
        if(!$result){
            $this->throwError("war file not found or is empty");
        }
        
        try{
            $this->view->output('all-wars', $result);
        }catch(Exception $e){
            $this->throwError($e->getMessage());
        }
    }
}

?>