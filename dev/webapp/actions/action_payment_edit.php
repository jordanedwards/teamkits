<?php // include necessary libraries
require("../includes/init.php");
$page_id = $_REQUEST["page_id"];
$action = ($_GET['action'] != "delete" ? "edit" : "delete");
require(INCLUDES . "/acl_module.php");

$item_name = payment;
	$payment_id=$_REQUEST["payment_id"];
		$payment_order_id=$_POST["payment_order_id"];
		$payment_transaction_number=$_POST["payment_transaction_number"];
		$payment_amount=$_POST["payment_amount"];
		$payment_method=$_POST["payment_method"];
		$payment_status=$_POST["payment_status"];
			// add the new record to the database
	include(CLASSES . "class_payment.php");
	
		$payment = new Payment();
		$payment->get_by_id($payment_id);
		$payment->set_order_id($payment_order_id);
		$payment->set_transaction_number($payment_transaction_number);
		$payment->set_amount($payment_amount);
		$payment->set_method($payment_method);
		$payment->set_status($payment_status);

if ($_POST['delete'] == "Delete" || $_REQUEST['action'] == "delete"){	
	if($payment->delete() == true) {
		$session->setAlertMessage("The $item_name has been removed successfully.");
		$session->setAlertColor("green");
		header("location:".$_SERVER['HTTP_REFERER']);	
//		header("location:". BASE_URL."/orders_edit.php?id=".$payment->get_order_id());
		exit;
	}
	else {
		$session->setAlertMessage("There was a problem removing the $item_name. Please try again.");
		$session->setAlertColor("yellow");	
		header("location:".$_SERVER['HTTP_REFERER']);
		exit;
	}

} else{

	if($payment->save() == true) {
		//Check if new record
		if($payment_id > 0){
			$session->setAlertMessage("The $item_name has been updated successfully.");
			$session->setAlertColor("green");
			header("location:". BASE_URL."/orders_edit.php?id=".$payment->get_order_id());
			exit;		
		}else{
			$session->setAlertMessage("The $item_name has been added successfully.");
			$session->setAlertColor("green");
			header("location:". BASE_URL."/orders_edit.php?id=".$payment->get_order_id());
			exit;
		}
	}
	else {
		$session->setAlertMessage("There was a problem adding/updating the Payment. Please make sure all fields are correct.");
		$session->setAlertColor("yellow");
		header("location:".$_SERVER['HTTP_REFERER']);
		exit;
	}	
}