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
		/*	if ($appConfig["environment"] != "local_development"):
				include_once(CLASSES . 'class_phpmailer.php');
				
				$body = '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><body><p>An unexpected error occured.</p>';
				$body .= "<p>File: ". $e->getFile()."</p>"; 
				$body .= "<p>Line: ". $e->getLine()."</p>";
				$body .= "<p>Reason: ". $e->getMessage()."</p>";
				
				$body .= "<p><br /><br />The " . $appConfig['app_title'] ." Team</p></body></html>";
				
				$mail = new PHPMailer();
				$mail->From = $admin_email;
				$mail->AddAddress($admin_email,"");
				$mail->Subject = $appConfig['app_title'] . " - System Error Occurred [" . $appConfig["environment"] ."]";
				$mail->Body = $body;
				
				$result = 1;
							
				if(!$mail->Send()) {
					$result = 0;
				}
				$mail = null;
			endif;
			*/
			addTolog($e->getMessage());
			$_SESSION['alert_msg'] = "An unexpected system error has occurred. The " . $appConfig['app_title'] ." administrators have been notified. We apologize for any inconvenience.";	
			$_SESSION['alert_color'] = "red";
			header("location:/error.php");
			exit;	
		}
		catch(Exception $e) {
			$_SESSION['alert_msg'] = "An unexpected system error has occurred. The " . $appConfig['app_title'] ." administrators have been notified. We apologize for any inconvenience.";	
			$_SESSION['alert_color'] = "red";
			header("location:/error.php");		
			exit;
		}
	}
}

?>