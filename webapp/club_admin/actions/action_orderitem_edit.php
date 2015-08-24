<?php // include necessary libraries
require("../../includes/init.php");
$page_id = $_REQUEST["page_id"];
$action = ($_REQUEST['action'] != "delete" ? "edit" : "delete");
require(INCLUDES . "/acl_module.php");
require(CLASSES . "class_orders.php");


	$orderitem_id=$_REQUEST["id"];
	$orderitem_item_number=$_REQUEST["orderitem_item_number"];
	$orderitem_price=$_REQUEST["orderitem_price"];
	$orderitem_quantity=$_REQUEST["orderitem_quantity"];
	$orderitem_size=$_REQUEST["orderitem_size"];	
	$orderitem_discount=$_REQUEST["orderitem_discount"];
	
		// add the new record to the database
	include(CLASSES . "class_orderitem.php");
	
	$orderitem = new Orderitem();
	$orderitem->get_by_id($orderitem_id);
	$orderitem->set_item_number($orderitem_item_number);
	$orderitem->set_price($orderitem_price);
	$orderitem->set_quantity($orderitem_quantity);
	$orderitem->set_size($orderitem_size);		
	$orderitem->set_discount($orderitem_discount);

	$order = new Orders();
	$order->get_by_id($orderitem->get_order_id());

if ($action == "delete"){	
	if($orderitem->delete() == true) {
		$order->recalculate();
		$order->save();

		$session->setAlertMessage("The Order item has been removed successfully.");
		$session->setAlertColor("green");
		header("location:". BASE_URL."/club_admin/" . "orders_edit.php?id=".$orderitem->get_order_id());
		exit;
	}
	else {
		$session->setAlertMessage("There was a problem removing the Order item. Please try again.");
		$session->setAlertColor("yellow");	
		header("location:". BASE_URL."/club_admin/" . "orders_edit.php?id=".$orderitem->get_order_id());
		exit;
	}

} else{

	if($orderitem->save() == true) {
		$order->recalculate();
		$order->save();	
		//Check if new record
		if($orderitem_id > 0){
			$session->setAlertMessage("The Order item has been updated successfully.");
			$session->setAlertColor("green");
			header("location:". BASE_URL."/club_admin/" . "orders_edit.php?id=".$orderitem->get_order_id());
			exit;		
		}else{
			$session->setAlertMessage("The Order item has been added successfully.");
			$session->setAlertColor("green");
			header("location:". BASE_URL."/club_admin/" . "orderitem_edit.php?id=".$orderitem->get_id());
			exit;
		}
	}
	else {
		$session->setAlertMessage("There was a problem adding/updating the Orderitem. Please make sure all fields are correct.");
		$session->setAlertColor("yellow");
		header("location:". BASE_URL."/club_admin/" . "orders_edit.php?id=".$orderitem->get_order_id());
		exit;
	}	
}