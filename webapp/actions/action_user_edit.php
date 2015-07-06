<?php // include necessary libraries
require("../includes/init.php");
$page_id = $_REQUEST["page_id"];
$action = ($_GET['action'] != "delete" ? "edit" : "delete");
require(INCLUDES . "/acl_module.php");

$item_name = "user";

		$user_id=$_REQUEST["user_id"];
		$user_first_name=$_REQUEST["user_first_name"];
		$user_last_name=$_REQUEST["user_last_name"];
		$user_email=$_REQUEST["user_email"];
		$user_tel=$_REQUEST["user_tel"];
		$user_password=$_REQUEST["user_password"];
		$user_login=$_REQUEST["user_login"];
		$user_role=$_REQUEST["user_role"];
			// add the new record to the database
		include_once(CLASSES . "class_user.php");

		$user = new User();
		$user->get_by_id($user_id);
		$user->set_first_name($user_first_name);
		$user->set_last_name($user_last_name);
		$user->set_email($user_email);
		$user->set_tel($user_tel);
		$user->set_password($user_password);
		$user->set_login($user_login);
		$user->set_role($user_role);
		if ($user_id == 0){
			$user->set_active("Y");
		}

if ($_REQUEST['action'] == "delete"){	
	if($user->delete() == true) {
		$session->setAlertMessage("The $item_name has been removed successfully.");
		$session->setAlertColor("green");
		header("location:".$_SERVER['HTTP_REFERER']);
		exit;
	}
	else {
		$session->setAlertMessage("There was a problem removing the $item_name. Please try again.");
		$session->setAlertColor("yellow");	
		header("location:".$_SERVER['HTTP_REFERER']);
		exit;
	}

} else{

	if($user->save() == true) {
		//Check if new record
		if($user_id > 0){
			$session->setAlertMessage("The $item_name has been updated successfully.");
			$session->setAlertColor("green");
			header("location:". BASE_URL."/" . $item_name . "_list.php?page=".$session->getPage());
			exit;		
		}else{
			$session->setAlertMessage("The $item_name has been added successfully.");
			$session->setAlertColor("green");
			header("location:". BASE_URL."/" . $item_name . "_edit.php?id=".$user->get_id());
			exit;
		}
	}
	else {
		$session->setAlertMessage("There was a problem adding/updating the $item_name. Please make sure all fields are correct.");
		$session->setAlertColor("yellow");
		header("location:".$_SERVER['HTTP_REFERER']);
		exit;
	}	
}