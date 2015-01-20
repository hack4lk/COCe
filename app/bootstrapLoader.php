<?php
/*
 * Bootstrap loader for application files.
 * This file can be extended for further functionality
 */

/*
 * This the main config file for the application as well as other core files
 */ 
require_once('core/config.php'); 
require_once('core/class.router.php');
require_once('core/class.renderer.php');
require_once('core/interface.controller.php');
/*
 * The main/core class file which does all the efficiency calculations
 */
require_once('core/class.coce.php');

/*
 * The main data manager class that deals with reading and writing to disk
 */
require_once('models/class.datamanager.php');

?>