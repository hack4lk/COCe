<?php
/*
 * The DataManager class in designed to write and read data
 * from the filesystem as well as parse data
 */
 
class DataManager extends FileManager{
    
    private $warData = array();
    private $warStats = array();
    private $warDate = "";
    private $coce;
    
    public function __construct(){
        $this->coce = new COCE();
    }
    
    public function getAllWarStats(){
        $warData = "";
        $warFile = "__ALL_WAR_STATS.json";
        
        if(!$warData = $this->loadDataFile($warFile)){
            return false;
        }else{
            return $warData;
        }
    }
    
    public function getDataForSpecificWar($date){
        $warData = "";    
        $warFile = $date . ".json";
        if(!($warData = $this->loadDataFile($warFile))){
            return false;
        }else{
            //this means the file exists
            return $warData;
        }
    }
    
    //this method checks the integrity of the csv file
    //being loaded to make sure data is in correct format
    public function checkAndFormatData($rawData){
        $data = explode("\r", $rawData);
        
        if(count($data) === 1){
            $data = explode("\n", $rawData);
        }
        
        if(count($data) === 1){
            return false;   
        }
        
        $date = explode(",", $data[0]);
        $finalData = array();
        
        if(!preg_match("/^[0-9]+\/[0-9]+\/[0-9]+$/", $date[0])){
            return false;
        }else{
            //convert data to dash instead of forward slash
            $tempDate = $date[0];
            $tempDate = str_replace("/", "-", $date[0]);
            $finalData['__War::Date__'] = $tempDate;
            
            
            foreach($data as $key =>$val){
               $row = explode(",", $val);
                
               if(isset($row[1] ) && $row[1] != "" && preg_match("/^[0-9]+$/", $row[1]) ){
                   $finalData[$row[0]] = array(
                        'HB'    => $row[1],
                        'OP1'   => $row[2],
                        'OP2'   => $row[3],
                        'N1'    => $row[4],
                        'N2'    => $row[5]
                   );
               }
            }
            return $finalData;
        }
    }
    
    public function setDataForSpecificWar($arrayData = ""){
        if($arrayData == "" || empty($arrayData)){
            return false;
        }else{
            $fname = DATA_PATH . '/' . $arrayData['__War::Date__'] . '.json';
            $data = json_encode($arrayData);
            if(file_put_contents($fname, $data)){
                return true;
            }else{
                return false;
            }
        }
    }
    
    public function setStatsForSpecificWar($arrayData = ""){
        $masterData = array();
        if($arrayData == "" || empty($arrayData)){
            return false;
        }else{
            $this->warData = $arrayData;
            $this->warDate = $arrayData['__War::Date__'];
            $warFile = DATA_PATH . '/' . $this->warDate .'_stats.json';
        
            $this->warStats = $this->parseRelavanceData();
            $masterData[$arrayData['__War::Date__']] = $this->warStats;
            //we have all the data...now just serialize the entire data and write to file
            $sData = json_encode($masterData);
            
            if($this->saveDataFile($warFile, $sData)){
                //if everything goes smooth we'll update the
                //master stats file 
                return $this->updateMasterStatsFile();
            }else{
                $this->throwError('ERROR: could not update war stats file');
            }
        }
    }
    
    private function updateMasterStatsFile(){
         $warFile = DATA_PATH . '/' . '__ALL_WAR_STATS.json';
         $tempData = array();
         
         //grab just the general stats
         $maximums = $this->warStats['__Maximums__'];
         $averages = $this->warStats['__Averages__'];
         $tempData[$this->warDate] = array(
            '__Maximums__'  => $maximums, 
            '__Averages__'  => $averages
         );
         
         if(!$this->fileExists($warFile)){
            $tempData = json_encode($tempData);
            if($this->saveDataFile($warFile, $tempData)){
                return true;
            }else{
                $this->throwError("ERROR: could not create master stats file.");
            }
         }else{
            $existingData = $this->loadDataFile('__ALL_WAR_STATS.json');  
            $existingData = json_decode($existingData, true);
            
            $maximums = $this->warStats['__Maximums__'];
            $averages = $this->warStats['__Averages__'];
            $existingData[$this->warDate] = array(
                '__Maximums__'  => $maximums, 
                '__Averages__'  => $averages
            );
                
            $existingData = json_encode($existingData);
            if($this->saveDataFile($warFile, $existingData)){
                return true;
            }else{
                $this->throwError("ERROR: could not update master stats file.");
            }
         }
    }
    
    public function parseRelavanceData(){
       $tempData = $this->warData;
       $statData = array();
       
       $statData['__Maximums__'] = array(
            'Single'    => 0,
            'wSingle'   => 0,
            'War'       => 0,
            'wWar'      => 0,
            'War_NoP'   => 0,
            'wWar_NoP'  => 0
       );
       
       $statData['__Averages__'] = array(
           'Single'    => 0,
           'wSingle'   => 0,
           'War'       => 0,
           'wWar'      => 0,
           'War_NoP'   => 0,
           'wWar_NoP'  => 0
       );
       
       //remove date key in array because we don't need that
       unset($tempData['__War::Date__']);
       
       //get size of clan at war time...
       $clansize = count($tempData);
       //loop through array and use COCE class to assign efficieny
       //values to each attack amongst other values
       foreach($tempData as $key => $val){
           $params1 = array(
                'HP'=> $val['HB'],
                'OP'=> $val['OP1'],
                'N' => $val['N1']
           );
           $params2 = array(
                'HP'=> $val['HB'],
                'OP'=> $val['OP2'],
                'N' => $val['N2']
           );
           
           $Efficiency1 = round($this->coce->getSingleAttackEfficiency($params1));
           $Efficiency2 = round($this->coce->getSingleAttackEfficiency($params2));
           $WeightedEfficiency1 = round($this->coce->getSingleAttackEfficiency($params1, true, $clansize));
           $WeightedEfficiency2 = round($this->coce->getSingleAttackEfficiency($params2, true, $clansize));
           $WarEfficiency = $this->coce->getWarEfficiency($Efficiency1, $Efficiency2);
           $weightedWarEfficiency = $this->coce->getWarEfficiency($WeightedEfficiency1, $WeightedEfficiency2);
           $WarEfficiencyWithoutPenalty = $this->coce->getWarEfficiency($Efficiency1, $Efficiency2, false);
           $weightedWarEfficiencyWithoutPenalty = $this->coce->getWarEfficiency($WeightedEfficiency1, $WeightedEfficiency2, false);
           
           //keep adding averages....we'll divide later
           $statData['__Averages__']['Single'] +=  $Efficiency1;
           $statData['__Averages__']['Single'] +=  $Efficiency2;
           $statData['__Averages__']['wSingle'] += $WeightedEfficiency1;
           $statData['__Averages__']['wSingle'] += $WeightedEfficiency2;
           $statData['__Averages__']['War'] += $WarEfficiency;
           $statData['__Averages__']['wWar'] += $weightedWarEfficiency;
           $statData['__Averages__']['War_NoP'] += $WarEfficiencyWithoutPenalty;
           $statData['__Averages__']['wWar_NoP'] += $weightedWarEfficiencyWithoutPenalty;
           
           //set all the maximums
           if($statData['__Maximums__']['Single'] < $Efficiency1) $statData['__Maximums__']['Single'] = $Efficiency1;
           if($statData['__Maximums__']['Single'] < $Efficiency2) $statData['__Maximums__']['Single'] = $Efficiency2    ;
           
           if($statData['__Maximums__']['wSingle'] < $WeightedEfficiency1) $statData['__Maximums__']['wSingle'] = $WeightedEfficiency1;
           if($statData['__Maximums__']['wSingle'] < $WeightedEfficiency2) $statData['__Maximums__']['wSingle'] = $WeightedEfficiency2;
           
           if($statData['__Maximums__']['War'] < $WarEfficiency) $statData['__Maximums__']['War'] = $WarEfficiency;
           if($statData['__Maximums__']['wWar'] < $weightedWarEfficiency) $statData['__Maximums__']['wWar'] = $weightedWarEfficiency;
           if($statData['__Maximums__']['War_NoP'] < $WarEfficiencyWithoutPenalty) $statData['__Maximums__']['War_NoP'] = $WarEfficiencyWithoutPenalty;
           if($statData['__Maximums__']['wWar_NoP'] < $weightedWarEfficiencyWithoutPenalty) $statData['__Maximums__']['wWar_NoP'] = $weightedWarEfficiencyWithoutPenalty;
           
               
           ///write the remining single person data to array
           $statData[$key] = array(
                'E1'           => $Efficiency1,
                'E2'           => $Efficiency2,
                'wE1'  => $WeightedEfficiency1,
                'wE2'  => $WeightedEfficiency2,
                'WarE'                         => $WarEfficiency,
                'wWarE'                 => $weightedWarEfficiency,
                'WarE_NoP'           => $WarEfficiencyWithoutPenalty,
                'wWarE_NoP'   => $weightedWarEfficiencyWithoutPenalty,
           );
           
       }

       //after the loop, we average out the actual averages
       $statData['__Averages__']['Single'] =  round($statData['__Averages__']['Single'] / $clansize);
       $statData['__Averages__']['wSingle'] =  round($statData['__Averages__']['wSingle'] / $clansize);
       $statData['__Averages__']['War'] =  round($statData['__Averages__']['War'] / $clansize);
       $statData['__Averages__']['wWar'] =  round($statData['__Averages__']['wWar'] / $clansize);
       $statData['__Averages__']['War_NoP'] =  round($statData['__Averages__']['War_NoP'] / $clansize);
       $statData['__Averages__']['wWar_NoP'] =  round($statData['__Averages__']['wWar_NoP'] / $clansize);
       
       return $statData;
    }
}

?>