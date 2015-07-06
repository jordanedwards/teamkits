<?php
//Need to figure out how to use ACL with ajax, as the page id gets pretty abstract as this can be accessed from any page.
require("../includes/init.php"); 
require(CLASSES . "class_orderitem.php");
require(CLASSES . "class_orders.php");

$orderitem = new Orderitem();
$order = new Orders();
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

//Either the order id has to be set (adding an item), or the order item id (editing/deleting an item)
if (isset($_REQUEST['order_id']) || isset($_REQUEST['order_item_id'])){
	$order_id = escaped_var_from_post('order_id');
	$order_item_id = escaped_var_from_post('order_item_id');
	$action = escaped_var_from_post("action");
	$item_id = escaped_var_from_post("item_id");
	$item_quantity = escaped_var_from_post("item_quantity");

// DELETING AN ITEM
	if ($action == "delete"):
		$orderitem->get_by_id($item_id);
		
		if($orderitem->delete() == true) {}
		else {
			$alertMessage = "There was a problem removing the order item. Please try again.";
			$alertColor = "yellow";	
		}
	endif;
	
// ADDING A NEW ITEM
	if ($action == "add"):	
		if (!$order_id > 0){
			// No order id given, so start by looking up any open orders: (function loads order if found)
			if(!$order->get_open_order($club_id)){
				// No open order exists, so create a new one:
				$order->set_club_id($club_id);
				$order->set_status(6);
				$order->save();
			}
		} else {
			// order id is given in POST, so load it:
			$order->get_by_id($order_id);
		}
		
		// Increment item quantity:
		$existing_order_item = $order->find_existing_order_item($item_id);
		if ($existing_order_item > 0){
			$orderitem->get_by_id($existing_order_item);
			$old_quantity = $orderitem->get_quantity();
			$orderitem->set_quantity($old_quantity+$item_quantity);
			$orderitem->set_price($orderitem->get_item_price());			
		} else {
		// No existing record of this item in this order, so continue populating object from posted variables:
			$orderitem->set_order_id($order_id);
			$orderitem->set_item_number($item_id);
			$orderitem->set_quantity($item_quantity);
			$orderitem->get_item_details();
			$orderitem->set_price($orderitem->get_item_price());
			$orderitem->set_active("Y");
		}
		
		if($orderitem->save() == true) {
			// Success;
			// Create JSON object with:
			// Success/failure
			// Order item id
			// Order item price
			$return_vals = array('success'=>true,'order_item_id'=>$orderitem->get_id(),'order_item_price'=>$orderitem->get_price());
		}
		else {
			$return_vals = array('success'=>false,'msg'=>"Unable to save - check debug log");
		}	
	endif;

} else {
	$return_vals = array('success'=>false,'msg'=>"Order id or order item id not set");
}

// Return JSON at the end
echo json_encode($return_vals);
?>