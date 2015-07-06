<?php
	ob_start();
	session_start();
	date_default_timezone_set("America/Vancouver");
// Sets the paths for includes for these folders. To access the URL itself, use $json_project_settings array
	define("BASE_FOLDER",$_SERVER['DOCUMENT_ROOT'] . "/teamkits");
	define("INCLUDES", BASE_FOLDER . "/includes/");
	define("CLASSES", BASE_FOLDER . "/classes/");
	define("ACTIONS", BASE_FOLDER . "/actions/");

define("BASE_URL", "/teamkits");
	define("INCLUDES_URL", BASE_URL . "/includes");
	define("CLASS_URL", BASE_URL . "/classes/");
	define("ACTIONS_URL", BASE_URL . "/actions/");
	
	require_once(INCLUDES . '/config_app.php');
	require_once(CLASSES . '/class_session_manager.php');
	require_once(CLASSES . '/class_functions.php');
	require_once(CLASSES . '/class_data_manager.php');
	include_once(CLASSES . '/class_drop_downs.php');	

	define("HEAD", INCLUDES . "head.php");	
	define("INCLUDES_LIST", INCLUDES . "includes.php");
		
	// Get settings:
	//Show if you have a settings table and want to write the settings as constants:
	$dm = new DataManager(); 
	$strSQL = "SELECT * FROM settings";						

	$result = $dm->queryRecords($strSQL);	
	if($result):
		while($row = mysqli_fetch_assoc($result)):
			$const_name = strtoupper(str_replace(" ","_",$row['settings_name']));
			define($const_name,$row['settings_value']);
		endwhile;
	endif;
		
	$session = new SessionManager();
		
	$alert_msg = $session->getAlertMessage();
	$alert_color = $session->getAlertColor();
	
	$admin_email = "jordan@orchardcity.ca";
// ****************************************** USER NOT LOGGED IN **********************************	
if($session->get_user_id() == "" && $public != true):

// ************************************** LIST OF PUBLIC ACCESS PAGES *****************************
	// Add any public access pages to the array:
	$publicPageArray = array(
	BASE_URL . "/index.php",
	BASE_URL . "/actions/action_login_user.php",
	BASE_URL . "/forgot_password.php",
	BASE_URL . "/actions/action_forgot_password_admin.php"
	);
	
// Additional public pages may be added to the list, or simply set the $public variable to true in your file before calling init.php	
		
	if (!in_array($_SERVER['PHP_SELF'], $publicPageArray)):
	
		$current_adr = str_replace("?","*",$_SERVER["REQUEST_URI"]);
		$current_adr = str_replace("&","~",$current_adr);
		
// ****************************************** SET TO YOUR LOGIN PAGE **********************************		
		header("location: " . BASE_URL . "/index.php?redirect=".$current_adr );
		exit;
	endif;
endif;