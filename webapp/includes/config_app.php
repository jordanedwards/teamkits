<?php
// Application Configuration File
// set application variables

// The Development environment operates differently from the production environment:
/*
	- All errors are displayed
	- Dev database may be used (set in config_db.php
	- Email sent through the phpmailer class will be sent to the admin email set in init.php ONLY
	- SQL queries are logged to the console
	- APIs will use dev keys (set in init.php);
*/
$appConfig['app_title'] = "Teamkits"; 

error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('display_errors',1);
ini_set('log_errors',1);
ini_set('log_errors_max_len',0);
ini_set('error_log','/error_log.txt');

// Discover which environment we are in:
	
if($_SERVER['HTTP_HOST'] == 'localhost'):
 	error_reporting(E_ALL);
	$appConfig["environment"] = "local_development";
elseif($_SERVER['HTTP_HOST'] == 'dev.teamkits.net'):
	$appConfig["environment"] = "development";
	error_reporting(E_ERROR | E_WARNING  | E_PARSE);
else:
	$appConfig["environment"] = "production";
//	ini_set('display_errors', 'off');
	error_reporting(E_ERROR | E_WARNING  | E_PARSE);
endif;
