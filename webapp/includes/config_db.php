<?php
// Database Configuration File

	require('config_app.php');
	
	if ($appConfig["environment"] == 'development'){
		$dbConfig['dbhost'] = "";		
		$dbConfig['dbuser'] = "";
		$dbConfig['dbpass'] = "";		
		$dbConfig['dbname'] = "";		
	}elseif ($appConfig["environment"] == 'local_development'){
		$dbConfig['dbhost'] = "localhost";		
		$dbConfig['dbuser'] = "root";
		$dbConfig['dbpass'] = "";		
		$dbConfig['dbname'] = "info@teamkits.net";
	}else{
		// Production		
		$dbConfig['dbhost'] = "localhost";		
		$dbConfig['dbuser'] = "orchardc_teamkit";
		$dbConfig['dbpass'] = "2121Springfield";		
		$dbConfig['dbname'] = "orchardc_teamkits";
	}