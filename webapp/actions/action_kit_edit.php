<?php // include necessary libraries
require("../includes/init.php");
$page_id = $_REQUEST["page_id"];
$action = ($_REQUEST['action'] != "delete" ? "edit" : "delete");

require(INCLUDES . "/acl_module.php");
require(CLASSES . "class_kit.php");

$item_name = ucfirst("kit");
$action=escaped_var_from_post('action');
$id = escaped_var_from_post('id');
$is_active = escaped_var_from_post('is_active');

$component = new Kit();

if ($id > 0){
	$component->get_by_id($id);
}
$component->load_from_post($_POST);
if (!isset($is_active)){
	$component->set_is_active("Y");
}
	
if ($action == "delete"){	
	if($component->delete("full") == true) {
		$session->setAlertMessage("The $item_name has been removed successfully.");
		$session->setAlertColor("green");
			header("location:". BASE_URL."/club_edit.php?id=".$component->get_club_id());
		exit;
	}
	else {
		$session->setAlertMessage("There was a problem removing the $item_name. Please try again.");
		$session->setAlertColor("yellow");	
			header("location:". BASE_URL."/club_edit.php?id=".$component->get_club_id());
		exit;
	}

} elseif($action == "add" || $action == "edit"){
	// add the new record to the database

	if($component->save() == true) {
		//Check if new record
		if($id > 0){
			$session->setAlertMessage("The $item_name has been updated successfully.");
			$session->setAlertColor("green");
			header("location:". BASE_URL."/club_edit.php?id=".$component->get_club_id());
			exit;		
		}else{
			$session->setAlertMessage("The $item_name has been added successfully.");
			$session->setAlertColor("green");
			header("location:".$_SERVER['HTTP_REFERER']);
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