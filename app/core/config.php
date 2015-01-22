<?php
/*
 * The main config file for the application.
 * These variables may need to be updated based on user's servers but
 * most of these settings should work just fine out of the box
 */

/*
 * If you installed your applicaiton outside of the root of your website, you will
 * need to put that path below.
 *  
 * So, if you installed your application at 'www.example.com/my/application'
 * the $dir would be equal to '/my/application'
 */
$dir = "/coc/dev/COCe";


/*
 * This is the fully qualified url of your website, e.g.'http://www.examople.com' - 
 * Do Not add the trailing / at the end
 */
 define("APP_URL", "http://localhost/coc/dev/COCe");
 
/*
 * VERY IMPORTANT! - Update this token below. This is the key you will be using when
 * doing administrative things like upload war files. Make it as difficult as possible
 * for someone to guess but easy enough for you to remember as you will need to type it
 * into forms.
 */
define("AUTH_TOKEN", "V3RY_$3cReT_K3Y"); 
 
 
/*
 * These variables below should work just fine but you can update them if you want to
 * use different paths to store and retrieve your data
 */
$fullPath = $_SERVER["DOCUMENT_ROOT"] . $dir; 
define('APP_PATH', "{$fullPath}/app");
define('DATA_PATH', "{$fullPath}/data");
define('UPLOAD_PATH', "{$fullPath}/data/uploads");

?>