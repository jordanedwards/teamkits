<?php // include necessary libraries
require("../includes/init.php");
$page_id = $_REQUEST["page_id"];
$action = ($_GET['action'] != "delete" ? "edit" : "delete");
require(INCLUDES . "/acl_module.php");

	$order_id=$_POST["order_id"];
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
		$orders->set_id($order_id);
		$orders->set_club_id($order_club_id);
		$orders->set_customer($order_customer);
		$orders->set_item($order_item);
		$orders->set_price($order_price);
		$orders->set_quantity($order_quantity);
		$orders->set_status($order_status);
		$orders->set_tracking_number($order_tracking_number);
		$orders->set_notes($order_notes);
		$orders->set_active($is_active);

	include_once(CLASSES . "class_user.php");
  	$last_updated_user = new User;
  	$last_updated_user->get_by_id($session->get_user_id());
	$orders->set_last_updated_user($last_updated_user->get_first_name().' '.$last_updated_user->get_last_name());

if ($_GET['action'] == "delete"){
	$dm = new DataManager();
	$id = mysqli_real_escape_string($dm->connection, $_GET['id']);
	
if($orders->delete_by_id($id) == true) {
		$session->setAlertMessage("The Orders has been removed successfully.");
		$session->setAlertColor("green");
		header("location:".$_SERVER['HTTP_REFERER']);
		exit;
	}
	else {
		$session->setAlertMessage("There was a problem removing the Orders. Please try again.");
		$session->setAlertColor("yellow");	
		header("location:".$_SERVER['HTTP_REFERER']);
		exit;
	}

} else{

	if($orders->save() == true) {
		//Check if new record
		if($orders_id > 0){
			$session->setAlertMessage("The Orders has been updated successfully.");
			$session->setAlertColor("green");
			header("location:". BASE_URL."/" . "orders_list.php?page=".$session->getPage());
			exit;		
		}else{
			$session->setAlertMessage("The Orders has been added successfully.");
			$session->setAlertColor("green");
			header("location:". BASE_URL."/" . "orders_edit.php?id=".$orders->get_id());
			exit;
		}
	}
	else {
		$session->setAlertMessage("There was a problem adding/updating the Orders. Please make sure all fields are correct.");
		$session->setAlertColor("yellow");
		header("location:".$_SERVER['HTTP_REFERER']);
		exit;
	}	
}