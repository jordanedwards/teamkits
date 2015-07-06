<?php
// Database Configuration File

	require($_SERVER['DOCUMENT_ROOT'] .'/includes/config_app.php');
	
	if ($appConfig["environment"] == 'development'){
		$dbConfig['dbhost'] = "localhost";		
		$dbConfig['dbuser'] = "orchardc_je";
		$dbConfig['dbpass'] = "944cawston";		
		$dbConfig['dbname'] = "orchardc_jordanedwards";		
	}elseif ($appConfig["environment"] == 'local_development'){
		$dbConfig['dbhost'] = "localhost";		
		$dbConfig['dbuser'] = "root";
		$dbConfig['dbpass'] = "";		
		$dbConfig['dbname'] = "test";
	}else{
		// Production		
		$dbConfig['dbhost'] = "localhost";		
		$dbConfig['dbuser'] = "orchardc_je";
		$dbConfig['dbpass'] = "944cawston";		
		$dbConfig['dbname'] = "orchardc_jordanedwards";
	}