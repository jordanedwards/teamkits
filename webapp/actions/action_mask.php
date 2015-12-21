<?php 
require("../includes/init.php"); 
require_once(CLASSES . "class_user.php"); 
$user = new User();
	
if (isset($_GET["return"])){
	$user_id = $user->get_by_id($session->getMask());	
		
	$session->set_user_id($user_id);
	$session->set_user_role($user->get_role());
	$session->setAlertColor("green");			
	$session->setAlertMessage("Returned to Admin");
	header("location: /webapp/dashboard.php");
	exit;
	
}
// make sure user is a superuser and club has a user id.
if(!isset($_GET["id"]) || $session->get_user_role() != 1) {
	$session->setAlertMessage("Unable to use mask function - club user id not set or current user is not an admin");
	$session->setAlertColor("red");	
	header("location: /webapp/dashboard.php");
	exit;
} 
	// Who are we going to be disguised as:
	$mask_user_id = $_GET["id"];
	// Keep track of who we really are so we can switch back later:
	$session->setMask($session->get_user_id());
	
	$user->get_by_id($mask_user_id);
	
	$redirect = BASE_URL . "/club_admin/dashboard_club.php";

	$session->set_user_id($mask_user_id);
	$session->set_user_role($user->get_role());
	$session->setAlertColor("green");			
	$session->setAlertMessage("Login successful - You are acting as: ". $user->get_user_name(). ", To return to your admin profile, Click on the profile name at the top right and choose 'Return'");
	header("location:" . $redirect);
	exit;
?>