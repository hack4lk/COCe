<?php
//test class for the COCE main class
require_once('../inc/class.coce.php');

class TestCOCE extends PHPUnit_FrameWork_TestCase{

    public function testSingleAttackEfficiency(){
        $params = array(
            'HP' => 12,
            'OP' => 15,
            'N'  => 3
        );
        
        $coce = new COCE();
        $result = $coce->getSingleAttackEfficiency($params); 
        $this->assertEquals($result, -1);
        
    }  
    
    public function testSingleAttackEfficiencyWithWeight(){
        $params = array(
            'HP' => 1,
            'OP' => 2,
            'N'  => 3
        );
        
        $coce = new COCE();
        $result = $coce->getSingleAttackEfficiency($params, true, 25); 
        $this->assertEquals($result, -1);
        
    }    
}


?>