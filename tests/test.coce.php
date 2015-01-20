<?php
//test class for the COCE main class
require_once('../inc/class.coce.php');

class TestCOCE extends PHPUnit_FrameWork_TestCase{

    public function testSingleAttackEfficiency(){
        $params = array(
            'HP' => 15,
            'OP' => 18,
            'N'  => 3
        );
        
        $coce = new COCE();
        $result = $coce->getSingleAttackEfficiency($params); 
        $this->assertEquals($result, 100);
        
    }  
    
    public function testSingleAttackEfficiencyWithWeight(){
        $params = array(
            'HP' => 15,
            'OP' => 18,
            'N'  => 3
        );
        
        $coce = new COCE();
        $result = $coce->getSingleAttackEfficiency($params, true, 25); 
        $this->assertEquals($result, 133);
        
    }    
    
    public function testWarEfficiency(){
        $coce = new COCE();
        $result = $coce->getWarEfficiency(86, 76);
        $this->assertEquals($result, 30);
    }
}
?>