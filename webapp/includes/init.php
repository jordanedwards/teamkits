<?php
	ob_start(); 
	date_default_timezone_set("America/Vancouver");
	
	define("BASE",$_SERVER['DOCUMENT_ROOT'] . "/webapp");
	define("INCLUDES", BASE . "/includes/");
	define("CLASSES", BASE . "/classes/");
	define("CSS", BASE . "/css/");
	define("SCRIPTS", BASE . "/js/");
	define("ACTIONS", BASE . "/actions/");

	define("BASE_URL", "/webapp");
	define("INCLUDES_URL", BASE_URL . "/includes");
	define("CLASSES_URL", BASE_URL . "/classes/");
	define("ACTIONS_URL", BASE_URL . "/actions/");

	require_once(INCLUDES . 'config_app.php');
	require_once(CLASSES . 'class_session_manager.php');
	require_once(CLASSES . 'class_functions.php');
	require_once(CLASSES . 'class_data_manager.php');
	include_once(CLASSES . 'class_drop_downs.php');	

	spl_autoload_register(function ($class) {
		include (CLASSES .'class_' . strtolower($class) . '.php');
	});
	
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

	// API Keys:
	if (STRIPE_ENVIRONMENT == "live" && $appConfig["environment"] != "development"){
		define("STRIPE_API_KEY",STRIPE_API_KEY_LIVE);
		define("STRIPE_API_KEY_PUBLIC",STRIPE_API_KEY_LIVE_PUBLIC);		
	} else {
		// testing
		define("STRIPE_API_KEY",STRIPE_API_KEY_TEST);
		define("STRIPE_API_KEY_PUBLIC",STRIPE_API_KEY_TEST_PUBLIC);		
	}
	// End API keys
	
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
	BASE_URL . "/members/actions/action_login.php",	
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