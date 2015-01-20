<?php
/*
 * Main class of COCe
 * Author: Lukasz Karpuk
 * URL: www.lukaszkarpuk.me
 * (C)2015 MIT License
 * 
 */
 
/*
REFERENCE VARIABLES
- Efficiency (E)
- Home Position (HP) - this is the position of your base during war
- Opponent Position (OP) - this is the position of the base you hit
- Number of stars (N) - number of stars gotten in the attack
- Position Weight (PW) - this adds more weight to attacker with higer rankings so an attacker ranked
  #3 in the clan will get more value hitting for 3 starts than #14 would for hitting for same 3
- Clan Size (CS) - number of people in clan war
- War Efficiency (WE) - efficiency for war based on all attacks

STATIC VARIABLES
- Base (B) = 100 by default
- Normalization coefficient (n) = [value] / n * 100 -->this type of normalization sets
  the base efficiency value to 100 when attacker hits his same number for (n) stars...
  we are using this base value for all calculations. This also puts weight on getting
  more stars in attacks rather then hitting tougher opponents

DERIVED VARIABLES
- Position Offset(PO) = (HB-OP) - offset used to add weight to attacks 

SINGLE ATTACK EFFICIENCY FORMULA
-----------------------------------
E = [N*(1+PO/10)]/n*100

POSITION WEIGHT FORMULA
-----------------------------------
PW = 1+(1-log(CS)*HP)/10;
*/
 
 class COCE{
     
     private $CS = 0; //Clan size (number of people in clan war)
     private $E1 = 0; //effectiveness for single attack
     private $E2 = 0; //effectiveness used if multiple attacks are calculated
     private $HP = 0; //home base position
     private $OP = 0; //opponent base position
     private $PO = 0; //position offset used for coefficent
     private $N = 0; //number of stars gotten for attack
     private $PW = 0; //position weight 
     private $WE = 0; //war efficiency average
     public static $B = 100; //base variable
     public static $p = 10; //static coefficient  
     public static $n = 3; //sets how many stars equals base value of 100
     
     public function __construct(){
         //nothing for now
     }
     
     /**
      * method to get efficiency of a single attack
      * paramer passed is an array of data and output is a single number
      */
     public function getSingleAttackEfficiency($params, $usePositionWeight = false, $size = 0){
        $this->HP = isset($params['HP']) ? $params['HP'] : null;
        $this->OP = isset($params['OP']) ? $params['OP'] : null;
        $this->N = isset($params['N']) ? $params['N'] : null;
        //if any of the paramters are not passed correctly, we throw an error
        if($this->HP === null || $this->OP === null || $this->N === null){
            $this->showError(1); 
        }else{
            $this->PO = $this->HP - $this->OP;
            $this->E1 = ( $this->N * (1+$this->PO/10) ) / self::$n * 100;
            
            //if we want to add extra weight to the heavy hitters, here's where we do it...
            //this adds more value to hits the lower you are ranked in the clan
            if($usePositionWeight === true){
                if($size == 0){
                    $this->showError(2);
                }else{
                    //first lets find out where the person is in terms of clan size 
                    //and use that as the log base to get answers
                    $this->PW = (1+(1 - log($this->HP, $size))/2); 
                    // the /2 is to reduce the increase to something more reasonable
                    $this->E1 = round($this->E1*$this->PW);
                }
            }
            
            //if person hit way to high and became irrelevant, 
            //assign only the number of stars their received as value
            //instead of a negative score
            if($this->E1 <= $this->N) $this->E1 = $this->N;
             
            //return final value...
            return $this->E1;
        }
     }

    public function getWarEfficiency($score1 = 0, $score2 = 0, $attackPenalty = true){
        //do some error checking first    
        if($score1 === 0 && $score2 === 0){
            $this->showError(3);
        }        
        //attack penalty refers to penalizing attacker for not doing second attack
        if($attackPenalty === true){
            if($score2 === 0){
                $this->WE = $score1/2;
            }else{
                $this->WE = ($score1+$score2)/2;
            } 
        }else{
            if($score2 == 0){
                $this->WE = $score1;
            }else{
                $this->WE = ($score1+$score2)/2;
            } 
        }
        
        return $this->WE;
    }
     
     /*
      * Error class to throw class-level excemptions
      */
     private function showError($errNumb){
         $msg = "APPLICATION ERROR: ";
         switch ($errNumb) {
             case 1:
                 $msg .= "Wrong parameters passed for 'Single Attack Efficiency' calculation.";                
                 break;
             case 2:
                 $msg .= "To get a weighted ranking, you must input the size of the clan.";                
                 break;
             case 3:
                 $msg .= "Wrong number of parameters passed to war efficiency calculation.";                
                 break;
             default:
                 $msg .= "Issue processing request";
                 break;
         }
         
         throw new Exception($msg, 1);
         exit();
     }
 }

?>