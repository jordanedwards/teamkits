<?php // include necessary libraries
require("../includes/init.php");
$page_id = $_REQUEST["page_id"];
$action = ($_GET['action'] != "delete" ? "edit" : "delete");
require(INCLUDES . "/acl_module.php");

$itemName = "item";

		$item_id=$_POST["item_id"];
		$item_name=$_POST["item_name"];
		$item_price=$_POST["item_price"];
		$item_image=$_POST["item_image"];
		$item_brand=$_POST["item_brand"];
		$item_club_id=$_POST["item_club_id"];
		$is_active=$_POST["is_active"];
			// add the new record to the database
	include(CLASSES . "class_item.php");
	
		$item = new Item();
		$item->get_by_id($item_id);
		$item->set_name($item_name);
		$item->set_price($item_price);
		$item->set_image($item_image);
		$item->set_brand($item_brand);
		$item->set_club_id($item_club_id);
		$item->set_active($is_active);

if ($_GET['action'] == "delete"){	
	if($item->delete() == true) {
		$session->setAlertMessage("The $itemName has been removed successfully.");
		$session->setAlertColor("green");
		header("location:". BASE_URL."/" . $itemName . "_list.php?page=".$session->getPage());
		exit;
	}
	else {
		$session->setAlertMessage("There was a problem removing the $item_name. Please try again.");
		$session->setAlertColor("yellow");	
		header("location:".$_SERVER['HTTP_REFERER']);
		exit;
	}

} else{

	if($item->save() == true) {
		//Check if new record
		if($item_id > 0){
			$session->setAlertMessage("The $itemName has been updated successfully.");
			$session->setAlertColor("green");
			header("location:". BASE_URL."/" . $itemName . "_list.php?page=".$session->getPage());
			exit;		
		}else{
			$session->setAlertMessage("The $itemName has been added successfully. Please upload image(s)");
			$session->setAlertColor("green");
			header("location:". BASE_URL."/" . $itemName . "_edit.php?id=".$item->get_id());
			exit;
		}
	}
	else {
		$session->setAlertMessage("There was a problem adding/updating the $itemName. Please make sure all fields are correct.");
		$session->setAlertColor("yellow");
		header("location:".$_SERVER['HTTP_REFERER']);
		exit;
	}	
}