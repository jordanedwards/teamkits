<?php // include necessary libraries
require("../includes/init.php");
$page_id = $_REQUEST["page_id"];
$action = ($_REQUEST['action'] != "delete" ? "edit" : "delete");
require(INCLUDES . "/acl_module.php");

	$order_id=$_REQUEST["order_id"];
	$order_club_id=$_POST["order_club_id"];
	$order_customer=$_POST["order_customer"];
	$order_item=$_POST["order_item"];
	$order_price=$_POST["order_price"];
	$order_quantity=$_POST["order_quantity"];
	$order_status=$_POST["order_status"];
	$order_tracking_number=$_POST["order_tracking_number"];
	$order_notes=$_POST["order_notes"];
	$is_active=$_POST["is_active"];
		// add the new record to the database
	include(CLASSES . "class_orders.php");
	
		$orders = new Orders();
		if ($order_id > 0){
			$orders->get_by_id($order_id);
		}
		$orders->set_club_id($order_club_id);
		$orders->set_customer($order_customer);
		$orders->set_item($order_item);
		$orders->set_price($order_price);
		$orders->set_quantity($order_quantity);
		$orders->set_status($order_status);
		$orders->set_tracking_number($order_tracking_number);
		$orders->set_notes($order_notes);
		$orders->set_active($is_active);


if ($action == "delete"){	
	if($orders->delete_by_id($order_id) == true) {
		$session->setAlertMessage("The Order has been removed successfully.");
		$session->setAlertColor("green");
		header("location:".$_SERVER['HTTP_REFERER']);
		exit;
	}
	else {
		
		$session->setAlertMessage("There was a problem removing the Order. Please try again.");
		$session->setAlertColor("yellow");	
		header("location:".$_SERVER['HTTP_REFERER']);
		exit;
	}

} else{

	if($orders->save() == true) {
		//Check if new record
		if($order_id > 0){
			$session->setAlertMessage("The Order has been updated successfully.");
			$session->setAlertColor("green");
			header("location:". BASE_URL."/" . "orders_list.php?page=".$session->getPage());
			exit;		
		}else{
			$session->setAlertMessage("The Order has been added successfully.");
			$session->setAlertColor("green");
			header("location:". BASE_URL."/" . "orders_edit.php?id=".$orders->get_id());
			exit;
		}
	}
	else {
		$session->setAlertMessage("There was a problem adding/updating the Order. Please make sure all fields are correct.");
		$session->setAlertColor("yellow");
		header("location:".$_SERVER['HTTP_REFERER']);
		exit;
	}	
}