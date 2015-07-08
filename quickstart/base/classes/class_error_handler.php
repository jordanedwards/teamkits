<?php
//***********************************************************************************************
//  Class: ErrorHandler
//	Description: The ErrorHandler class handles all unexpected errors
//***********************************************************************************************
class ErrorHandler {

	//*******************************
	// class variables
	//*******************************
	
	//*******************************
	// constructor
	//*******************************
	function __construct() {
		/*******************************************************************************************************
		********************************************************************************************************/ 
	}


	//****************************************************************************************
	// Sends an email to the Admins and forwards the user to a generic error page
	//****************************************************************************************	
	public function notifyAdminException(Exception $e){
		try{
			require_once('../includes/init.php');
			$msg = "File: " . $e->getFile() . "<br>Line: " . $e->getLine(). "<br>Message: " .$e->getMessage();
			addTolog($msg,true);
			
			$_SESSION['alert_msg'] = "An unexpected system error has occurred. The " . $appConfig['app_title'] ." administrators have been notified. We apologize for any inconvenience.";	
			$_SESSION['alert_color'] = "red";
			header("location:/error.php?msg=".$msg);
			exit;	
		}
		catch(Exception $e) {
			$msg = "File: " . $e->getFile() . "
			Line: " . $e->getLine(). "
			Message: " .$e->getMessage();
			addTolog($msg,true);
			
			$_SESSION['alert_msg'] = "An unexpected system error has occurred. The " . $appConfig['app_title'] ." administrators have been notified. We apologize for any inconvenience.";	
			$_SESSION['alert_color'] = "red";
			header("location:/error.php?msg=".$msg);		
			exit;
		}
	}
}

?>