<?php require_once('inc/class.coce.php'); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>COCe</title>
        <link rel="stylesheet" href="css/bootstrap.min.css" />
        <script src="js/bootstrap.min.js"></script>
    </head>
    <?php 
        try{
            $coce = new COCE();
            $params = array(
                'HP' => 1,
                'OP' => 2,
                'N'  => 3
            );
            echo $coce->getSingleAttackEfficiency($params, true, 25); 
        }catch(Exception $e){
            echo $e->getMessage();
        }
        
        
    ?>
    
</html>