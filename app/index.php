<?php require_once('bootstrapLoader.php'); ?>

<?php 
/*
 * This is the main gateway file which grabs the task needed to send back to the front
 * and calls the correct method in the router class
 */
 
/*
 * The task statement below is tied directly to the controller file name, thus
 * when passing tasks, they need to be the same name as the filename
 */ 
$task = "";
if(isset($_GET['task']) && $_GET['task'] != ""){
    $task = htmlspecialchars($_GET['task'], ENT_QUOTES);
}else{
    die("ERROR: no task specified!");
}

/*
 * The method statement below is tied to the method of the controller file called above
 * and will throw an error if the method does not exist in the class
 */
$method = "";
if(isset($_GET['method']) && $_GET['method'] != ""){
    $method = htmlspecialchars($_GET['method'], ENT_QUOTES);
}

/*
 * The statement below tries to fetch the correct class (and methods if passed) using the router class
 */
if($task != ""){
    $router = new Router();
    try{
        $router->route($task, $method);
    }catch(Exception $e){
        die($e->getMessage());
    }
}else{
    die("ERROR: could not complete task request!");
}
?>