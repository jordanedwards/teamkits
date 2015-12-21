<?php
require("../includes/init.php"); 
require(CLASSES . "class_payment.php");
error_reporting(E_ALL);
ini_set('display_errors', '1');

//Either the order id has to be set (adding an item), or the order item id (editing/deleting an item)
if (isset($_GET['order_id'])){
	
	$action = escaped_var_from_post("action");
	$payment_id=escaped_var_from_post("payment_id");
	$payment_order_id=escaped_var_from_post("order_id");
	$payment_transaction_number=escaped_var_from_post("payment_transaction_number");
	$payment_amount=escaped_var_from_post("payment_amount");
	$payment_method=escaped_var_from_post("payment_method");
	$payment_status=escaped_var_from_post("payment_status");

	if ($action == "delete"):
		$payment->get_by_id($item_id);
		
		if($payment->delete() == true) {}
		else {
			$session->setAlertMessage("There was a problem removing the order item. Please try again.");
			$session->setAlertColor("yellow");	
			header("location:".$_SERVER['HTTP_REFERER']);
			exit;
		}
	endif;
	if ($action == "add"):
		$payment = new Payment();
		if ($payment_id > 0){
			$payment->get_by_id($payment_id);
		}
		$payment->set_order_id($payment_order_id);
		$payment->set_transaction_number($payment_transaction_number);
		$payment->set_amount($payment_amount);
		$payment->set_method($payment_method);
		$payment->set_status($payment_status);
		$payment->set_active("Y");
		
		if($payment->save() == true) {
			// Success;
			echo '<tr><td><a href="payment_edit.php?id=' . $payment->get_id() .'"><i class="fa fa-edit fa-lg"></i></a></td><td>' .number_format($payment_amount,2). '</td><td>' . $payment_method .'</td><td>' . date("m-d-y",time()).'</td></tr>';
		}
		else {
			echo("unable to save");
		}	
	endif;
} else {
	echo "<p>Order id not set</p>";
}
?>