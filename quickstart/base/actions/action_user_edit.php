<?php // include necessary libraries
require("../includes/init.php");
$page_id = $_REQUEST["page_id"];
$action = ($_REQUEST['action'] != "delete" ? "edit" : "delete");
require(INCLUDES . "/acl_module.php");
include_once(CLASSES . "class_user.php");

	$id=$_POST["id"];
	
	$user = new User();
	$user->get_by_id($id);
	
	// Only overwrite fields that are set
	if (isset($_POST["first_name"])){$user->set_first_name($_POST["first_name"]);}
	if (isset($_POST["last_name"])){$user->set_last_name($_POST["last_name"]);}
	if (isset($_POST["email"])){$user->set_email($_POST["email"]);}
	if (isset($_POST["tel"])){$user->set_tel($_POST["tel"]);}
	if (isset($_POST["carrier"])){$user->set_carrier($_POST["carrier"]);}
	if (isset($_POST["password"]) && strlen($_POST["password"])>2){$user->set_password($_POST["password"]);}
	if (isset($_POST["role"])){$user->set_role($_POST["role"]);}

if ($action == "delete"){
	$dm = new DataManager();
	$id = escaped_var_from_post("id");
	
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
		if($id > 0){
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