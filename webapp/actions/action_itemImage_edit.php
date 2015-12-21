<?php // include necessary libraries
require("../includes/init.php");
$page_id = $_REQUEST["page_id"];
$action = ($_GET['action'] != "delete" ? "edit" : "delete");
require(INCLUDES . "/acl_module.php");

$item_name = "itemImage";

		$itemImage_id=$_POST["itemImage_id"];
		$itemImage_item_id=$_POST["itemImage_item_id"];
		$itemImage_url=$_POST["itemImage_url"];
		$itemImage_description=$_POST["itemImage_description"];
		$is_active=$_POST["is_active"];
			// add the new record to the database
	include(CLASSES . "class_itemImage.php");
	
		$itemImage = new ItemImage();
		$itemImage->get_by_id($itemImage_id);
		$itemImage->set_item_id($itemImage_item_id);
		$itemImage->set_url($itemImage_url);
		$itemImage->set_description($itemImage_description);
		$itemImage->set_active($is_active);

if ($_GET['action'] == "delete" || $_REQUEST['delete'] == "Delete"){	
	if($itemImage->delete() == true) {
		$session->setAlertMessage("The $item_name has been removed successfully.");
		$session->setAlertColor("green");
			header("location:". BASE_URL."/" . $item_name . "_edit.php?id=".$itemImage->get_item_id());
		exit;
	}
	else {
		$session->setAlertMessage("There was a problem removing the $item_name. Please try again.");
		$session->setAlertColor("yellow");	
			header("location:". BASE_URL."/" . $item_name . "_edit.php?id=".$itemImage->get_item_id());
		exit;
	}

} else{

	if($itemImage->save() == true) {
		//Check if new record
		if($itemImage_id > 0){
			$session->setAlertMessage("The $item_name has been updated successfully.");
			$session->setAlertColor("green");
			header("location:". BASE_URL."/" . $item_name . "_edit.php?id=".$itemImage->get_item_id());
			exit;		
		}else{
			$session->setAlertMessage("The $item_name has been added successfully.");
			$session->setAlertColor("green");
			header("location:". BASE_URL."/" . $item_name . "_edit.php?id=".$itemImage->get_item_id());
			exit;
		}
	}
	else {
		$session->setAlertMessage("There was a problem adding/updating the $item_name. Please make sure all fields are correct.");
		$session->setAlertColor("yellow");
			header("location:". BASE_URL."/" . $item_name . "_edit.php?id=".$itemImage->get_item_id());
		exit;
	}	
}