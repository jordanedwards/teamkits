<?php 
require("../../includes/init.php"); 
error_reporting(E_ALL);
ini_set('display_errors', '1');

	// make sure we have valid login information
	if(!isset($_POST["club_code"])) {
		$session->setAlertMessage("No club code entered");
		$session->setAlertColor("red");	
		header("location: /login.php");
		exit;
	}
		
	$club_code = $_POST["club_code"];
	
	// If the user arrived at a login-only page, redirect them to this page after login.
	if (isset($_REQUEST['redirect']) && $_REQUEST['redirect'] != ""){
		$redirect = escaped_var_from_post("redirect");
		$redirect = str_replace("*","?",$_REQUEST["redirect"]);
		$redirect = str_replace("~","&",$redirect);
	} else {
		$redirect = BASE_URL . "/members/dashboard_member.php";
	}

	require_once(CLASSES . "class_user.php"); 	
	require_once(CLASSES . "class_club.php"); 
	$club = new Club();
	$club->get_by_code($club_code);

if($club->get_id() >0) {
	// if the user exists forward them to the dashboard, otherwise keep them at the login page with the appropriate login message

		$session->set_club($club->get_id());			
		$session->set_user_role(4);
		$session->setAlertColor("green");			
		$session->setAlertMessage("Login successful");
			header("location:" . $redirect);
		exit;
}
else {
		//The login failed so return the user to the login page with some error vars
		$session->setAlertMessage("Invalid club code. Please check your CAPS lock key and try again.");
		$session->setAlertColor("yellow");	
		header("location: /login.php");
		exit;
}
?>