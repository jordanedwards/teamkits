<?php // include necessary libraries
require("../includes/init.php");
$page_id = $_REQUEST["page_id"];
$action = ($_GET['action'] != "delete" ? "edit" : "delete");
require(INCLUDES . "/acl_module.php");

$item_name = "Item size";

		$itemSize_id=$_REQUEST["itemSize_id"];
		$itemSize_item_id=$_REQUEST["itemSize_item_id"];
		$itemSize_name=$_REQUEST["itemSize_name"];
		$itemSize_stock=$_REQUEST["itemSize_stock"];
		$is_active=$_REQUEST["is_active"];
			// add the new record to the database
	include(CLASSES . "class_itemSize.php");
	
		$itemSize = new ItemSize();
		if($itemSize_id > 0){
			$itemSize->get_by_id($itemSize_id);
		}
		$itemSize->set_item_id($itemSize_item_id);
		$itemSize->set_name($itemSize_name);
		$itemSize->set_stock($itemSize_stock);
		$itemSize->set_active($is_active);


if ($_GET['action'] == "delete"){	
	if($itemSize->delete() == true) {
		$session->setAlertMessage("The $item_name has been removed successfully.");
		$session->setAlertColor("green");
			header("location:". BASE_URL."/item_edit.php?id=".$itemSize_item_id);
		exit;
	}
	else {
		$session->setAlertMessage("There was a problem removing the $item_name. Please try again.");
		$session->setAlertColor("yellow");	
		header("location:".$_SERVER['HTTP_REFERER']);
		exit;
	}

} else{

	if($itemSize->save() == true) {
		//Check if new record
		if($itemSize_id > 0){
			$session->setAlertMessage("The $item_name has been updated successfully.");
			$session->setAlertColor("green");
			header("location:". BASE_URL."/item_edit.php?id=".$itemSize_item_id);
			exit;		
		}else{
			$session->setAlertMessage("The $item_name has been added successfully.");
			$session->setAlertColor("green");
			header("location:". BASE_URL."/item_edit.php?id=".$itemSize_item_id);
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