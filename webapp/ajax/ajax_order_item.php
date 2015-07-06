<?php
require("../includes/init.php"); 
require(CLASSES . "class_orderitem.php");
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

//Either the order id has to be set (adding an item), or the order item id (editing/deleting an item)
if (isset($_GET['order_id']) || isset($_GET['order_item_id'])){
	$order_id = escaped_var_from_post('order_id');
	$order_item_id = escaped_var_from_post('order_item_id');
	$action = escaped_var_from_post("action");
	$item_id = escaped_var_from_post("item_id");
	$item_quantity = escaped_var_from_post("item_quantity");

	$orderitem = new Orderitem();	

	include_once(CLASSES . "class_user.php");
  	$last_updated_user = new User;
  	$last_updated_user->get_by_id($session->get_user_id());
	$orderitem->set_last_updated_user($last_updated_user->get_first_name().' '.$last_updated_user->get_last_name());

	if ($action == "delete"):
		$orderitem->get_by_id($item_id);
		
		if($orderitem->delete() == true) {}
		else {
			$session->setAlertMessage("There was a problem removing the order item. Please try again.");
			$session->setAlertColor("yellow");	
			header("location:".$_SERVER['HTTP_REFERER']);
			exit;
		}
	endif;
	if ($action == "add"):
		$price = 0;
		$total = 0;
		$orderitem->set_order_id($order_id);
		$orderitem->set_item_number($item_id);
		$orderitem->set_quantity($item_quantity);
		$orderitem->get_item_details();
		$orderitem->set_price($orderitem->get_item_price());
		$orderitem->set_active("Y");
		
		if($orderitem->save() == true) {
			// Success;
			$subtotal = $item_quantity * $orderitem->get_price();
			echo '<tr><td><a href="orderitem_edit.php?id=' . $orderitem->get_id() .'"><i class="fa fa-edit fa-lg"></i></a></td><td>' .$orderitem->get_item_name(). '</td><td>' . $item_quantity .'</td><td>' . $orderitem->get_price().'</td><td>' . $subtotal . '</td></tr>';
		}
		else {
			exit("unable to save");
		}	
	endif;

} else {
	echo "<p>Order id or order item id not set</p>";
}
?>