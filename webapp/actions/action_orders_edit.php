<?php // include necessary libraries
require("../includes/init.php");
$page_id = $_REQUEST["page_id"];
$action = ($_REQUEST['action'] != "delete" ? "edit" : "delete");
require(INCLUDES . "/acl_module.php");
require(CLASSES . "class_orders.php");

$item_name = ucfirst("order");
$action=escaped_var_from_post('action');
$id = escaped_var_from_post('id');

$component = new Orders();

if ($id > 0){
	$component->get_by_id($id);
}
$component->load_from_post($_POST);

if ($currentUser->get_role() == 3){
	// redirect to the correct page if a club:
	$redirect =  BASE_URL."/club_admin/orders_list_club.php?page=".$session->getPage();
} else {
	$redirect = BASE_URL."/orders_list.php?page=".$session->getPage();
}

if ($action == "delete"){	
	if($component->delete() == true) {
		$session->setAlertMessage("The $item_name has been removed successfully.");
		$session->setAlertColor("green");
			header("location:". $redirect);
		exit;
	}
	else {
		$session->setAlertMessage("There was a problem removing the $item_name. Please try again.");
		$session->setAlertColor("yellow");	
			header("location:". $redirect);
		exit;
	}

} elseif($action == "add" || $action == "edit"){
	// add the new record to the database

	if($component->save() == true) {
		//Check if new record
		if($id > 0){
			$session->setAlertMessage("The $item_name has been updated successfully.");
			$session->setAlertColor("green");
			header("location:". $redirect);
			exit;		
		}else{
			$session->setAlertMessage("The $item_name has been added successfully.");
			$session->setAlertColor("green");
			header("location: ../orders_edit.php?id=".$component->get_id());
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