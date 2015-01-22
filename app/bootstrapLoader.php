<?php
/*
 * Bootstrap loader for application files.
 * This file can be extended for further functionality
 */

/*
 * This the main config file for the application as well as other core files
 */ 
require('core/config.php'); 
require('core/class.error.php');
require('core/class.router.php');
require('core/class.renderer.php');
require('core/class.controller.php');
require('core/class.filemanager.php');

/*
 * The class does all the efficiency calculations
 */
require('core/class.coce.php');

/*
 * The main model of the application. We autoload this becuse it's used frequently in the app
 */
require('models/class.datamanager.php');


/*
 * All helper functions which you want to autoload get loaded below
 */
 require('helpers/class.url.php');
?>