<?php // include necessary libraries
require("../includes/init.php");
$page_id = $_REQUEST["page_id"];
$action = ($_REQUEST['action'] != "delete" ? "edit" : "delete");
//require(INCLUDES . "/acl_module.php");

$item_name = "promo";

		$promo_id=$_REQUEST["promo_id"];
		$promo_sport=$_POST["promo_sport"];
		$promo_item_id=$_POST["promo_item_id"];
		$promo_title=$_POST["promo_title"];
		$promo_description=$_POST["promo_description"];
		$promo_view_type=$_POST["promo_view_type"];
		$promo_club_id=$_POST["promo_club_id"];
		$promo_price=$_POST["promo_price"];
		$promo_image=$_POST["promo_image"];
		$promo_expiry=$_POST["promo_expiry"];
		$is_active=$_POST["is_active"];
			// add the new record to the database
	include(CLASSES . "class_promo.php");
	
		$promo = new Promo();
		$promo->get_by_id($promo_id);
		$promo->set_sport($promo_sport);
		$promo->set_item_id($promo_item_id);
		$promo->set_title($promo_title);
		$promo->set_description($promo_description);
		$promo->set_view_type($promo_view_type);
		$promo->set_club_id($promo_club_id);
		$promo->set_price($promo_price);
		$promo->set_image($promo_image);
		$promo->set_expiry($promo_expiry);
		$promo->set_active($is_active);

if ($action == "delete"){	
	if($promo->delete() == true) {
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

	if($promo->save() == true) {
		//Check if new record
		if($promo_id > 0){
			$session->setAlertMessage("The $item_name has been updated successfully.");
			$session->setAlertColor("green");
			header("location:". BASE_URL."/" . $item_name . "_list.php?page=".$session->getPage());
			exit;		
		}else{
			$session->setAlertMessage("The $item_name has been added successfully.");
			$session->setAlertColor("green");
			header("location:". BASE_URL."/" . $item_name . "_edit.php?id=".$promo->get_id());
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