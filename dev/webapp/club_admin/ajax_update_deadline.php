<?php 
require_once("../includes/init.php"); 
require(CLASSES . "class_orders.php");
$order = new Orders();

$order_id= escaped_var_from_post("order_id");
$order_deadline = escaped_var_from_post("order_deadline");
	
if ($order_id > 0){
	$order->get_by_id($order_id );
	$order->set_deadline($order_deadline);
	$order->save();
}
?>