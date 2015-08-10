<?php 
require("../includes/init.php"); 
//	error_reporting(E_ALL);
//ini_set('display_errors', '1');

	// make sure we have valid login information
	if(!isset($_POST["email"]) || !isset($_POST["password"])) {
		$session->setAlertMessage("Invalid login. Please make sure all fields have been entered correctly and try again.");
		$session->setAlertColor("yellow");	
		header("location: /login.php");
		exit;
	}
		
	$email = $_POST["email"];
	$password = $_POST["password"];
	
	// If the user arrived at a login-only page, redirect them to this page after login.
	$redirect = escaped_var_from_post("redirect");
	$redirect = str_replace("*","?",$_REQUEST["redirect"]);
	$redirect = str_replace("~","&",$redirect);
	
	require_once(CLASSES . "class_user.php"); 
	$user = new User();
	$user->set_password($password);
	$user->set_email($email);
	$user_id = $user->login();

if($user_id != "") {
	// if the user exists forward them to the dashboard, otherwise keep them at the login page with the appropriate login message
	if (!isset($_REQUEST["redirect"])){
		switch ($user->get_role()){
			case "1":
				$redirect = BASE_URL . "/dashboard.php";
			break;
			case "2":
				$redirect = BASE_URL . "/dashboard.php";
			break;
			case "3":
				$redirect = BASE_URL . "/club_admin/dashboard_club.php";
			break;					
		}
	}	
		
		$user->update_last_login($user_id);
		$session->set_user_id($user_id);
		$session->set_user_role($user->get_role());
		$session->setAlertColor("green");			
		$session->setAlertMessage("Login successful");
			header("location:" . $redirect);
		exit;
}
else {
		//The login failed so return the user to the login page with some error vars
		$session->setAlertMessage("Incorrect email/password combination. Please check your CAPS lock key and try again.");
		$session->setAlertColor("yellow");	
		header("location: /login.php");
		exit;
}
?>