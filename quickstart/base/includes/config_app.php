<?php
// Application Configuration File
// set application variables

$appConfig['app_title'] = "Orchardcity"
// Discover which environment we are in:

error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('display_errors',0);
ini_set('log_errors',1);
ini_set('log_errors_max_len',0);
ini_set('error_log','./error_log.txt');
	
if($_SERVER['HTTP_HOST'] == 'localhost'):
	ini_set('display_errors',1); 
 	error_reporting(E_ALL);
	$appConfig["environment"] = "local_development";
else:
	$appConfig["environment"] = "production";	
endif;