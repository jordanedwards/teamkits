<?php // include necessary libraries
require("../includes/init.php");
$page_id = $_REQUEST["page_id"];
$action = ($_GET['action'] != "delete" ? "edit" : "delete");
require(INCLUDES_FOLDER . "/acl_module.php");
include_once(CLASS_FOLDER . "class_user.php");

	$user_id=$_POST["user_id"];
	
	$user = new User();
	$user->get_by_id($user_id);
	
	// Only overwrite fields that are set
	if (isset($_POST["user_first_name"])){$user->set_first_name($_POST["user_first_name"]);}
	if (isset($_POST["user_last_name"])){$user->set_last_name($_POST["user_last_name"]);}
	if (isset($_POST["user_email"])){$user->set_email($_POST["user_email"]);}
	if (isset($_POST["user_tel"])){$user->set_tel($_POST["user_tel"]);}
	if (isset($_POST["user_carrier"])){$user->set_carrier($_POST["user_carrier"]);}
	if (isset($_POST["user_password"]) && strlen($_POST["user_password"])>2){$user->set_password($_POST["user_password"]);}
	if (isset($_POST["user_role"])){$user->set_role($_POST["user_role"]);}

  	$last_updated_user = new User;
  	$last_updated_user->get_by_id($session->get_user_id());
	$user->set_last_updated_user($last_updated_user->get_first_name().' '.$last_updated_user->get_last_name());

if ($_GET['action'] == "delete"){
	$dm = new DataManager();
	$id = mysqli_real_escape_string($dm->connection, $_GET['id']);
	
if($user->delete_by_id($id) == true) {
		$session->setAlertMessage("The User has been removed successfully.");
		$session->setAlertColor("green");
		header("location:".$_SERVER['HTTP_REFERER']);
		exit;
	}
	else {
		$session->setAlertMessage("There was a problem removing the User. Please try again.");
		$session->setAlertColor("yellow");	
		header("location:".$_SERVER['HTTP_REFERER']);
		exit;
	}

} else{

	if($user->save() == true) {
		//Check if new record
		if($user_id > 0){
			$session->setAlertMessage("The User has been updated successfully.");
			$session->setAlertColor("green");
			header("location:". BASE_URL."/" . "user_list.php?page=".$session->getPage());
			exit;		
		}else{
			$session->setAlertMessage("The User has been added successfully.");
			$session->setAlertColor("green");
			header("location:". BASE_URL."/" . "user_list.php?page=".$session->getPage());
			exit;
		}
	}
	else {
		$session->setAlertMessage("There was a problem adding/updating the User. Please make sure all fields are correct.");
		$session->setAlertColor("yellow");
		header("location:".$_SERVER['HTTP_REFERER']);
		exit;
	}	
}