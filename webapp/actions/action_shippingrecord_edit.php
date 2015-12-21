<?php // include necessary libraries
require("../includes/init.php");
$page_id = $_REQUEST["page_id"];
$action = ($_GET['action'] != "delete" ? "edit" : "delete");
require(INCLUDES . "/acl_module.php");

	$shippingrecord_id=$_POST["shippingrecord_id"];
	$shippingrecord_carrier=$_POST["shippingrecord_carrier"];
	$shippingrecord_tracking=$_POST["shippingrecord_tracking"];
	$shippingrecord_date=$_POST["shippingrecord_date"];
		// add the new record to the database
	include(CLASSES . "class_shippingrecord.php");
	
		$shippingrecord = new Shippingrecord();
		
		$shippingrecord->get_by_id($shippingrecord_id);
		$shippingrecord->set_carrier($shippingrecord_carrier);
		$shippingrecord->set_tracking($shippingrecord_tracking);
		$shippingrecord->set_date($shippingrecord_date);

if ($_REQUEST['delete'] == "Delete"){	
	if($shippingrecord->delete() == true) {
		$session->setAlertMessage("The Shipping record has been removed successfully.");
		$session->setAlertColor("green");
		header("location:". BASE_URL."/" . "orders_edit.php?id=".$shippingrecord->get_order_id());
		exit;
	}
	else {
		$session->setAlertMessage("There was a problem removing the Shipping record. Please try again.");
		$session->setAlertColor("yellow");	
		header("location:".$_SERVER['HTTP_REFERER']);
		exit;
	}

} else{

	if($shippingrecord->save() == true) {
		//Check if new record
		if($shippingrecord_id > 0){
			$session->setAlertMessage("The Shippingrecord has been updated successfully.");
			$session->setAlertColor("green");
			header("location:". BASE_URL."/" . "orders_edit.php?id=".$shippingrecord->get_order_id());
			exit;		
		}else{
			$session->setAlertMessage("The Shippingrecord has been added successfully.");
			$session->setAlertColor("green");
			header("location:". BASE_URL."/" . "orders_edit.php?id=".$shippingrecord->get_order_id());
			exit;
		}
	}
	else {
		$session->setAlertMessage("There was a problem adding/updating the Shippingrecord. Please make sure all fields are correct.");
		$session->setAlertColor("yellow");
		header("location:".$_SERVER['HTTP_REFERER']);
		exit;
	}	
}