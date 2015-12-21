<?php /*
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors',1);
ini_set('log_errors',1);
ini_set('log_errors_max_len',0);
ini_set('error_log','error_log.txt');
*/

require("../includes/init.php"); 
require(CLASSES . "class_orderitem.php");
require(CLASSES . "class_orders.php");
require_once(CLASSES . "/class_user.php"); 
 
$orderitem = new Orderitem();
$order = new Orders();

$action = escaped_var_from_post("action");
$item_id = escaped_var_from_post("item_id");
	
//Either the order id has to be set (adding an item), or the order item id (editing/deleting an item)
if (isset($_REQUEST['order_id']) || isset($_REQUEST['order_item_id']) || isset($_REQUEST['item_id'])){
	$order_id = escaped_var_from_post('order_id');
	$order_item_id = escaped_var_from_post('order_item_id');
	$item_quantity = escaped_var_from_post("item_quantity");
	$item_size = escaped_var_from_post("item_size");
	$item_price = escaped_var_from_post("item_price");
	$notify_customer = escaped_var_from_post("notify_customer");	

// DELETING AN ITEM
	if ($action == "delete"):
		$orderitem->get_by_id($order_item_id);	
		if($orderitem->delete() == true) {
			$orderitem->set_success(true);
			$orderitem->setAlertMessage("Item deleted");
		}
		else {
			$orderitem->set_success(false);
			$orderitem->setAlertMessage("There was a problem removing the order item. Please try again.");
		}
	endif;
	
// ADDING A NEW ITEM
	if ($action == "add"){
		if ($order_id > 0){
			// order id is given in POST, so load it:
			$order->get_by_id($order_id);
		}else{
			exit("No order id given");
		}
				
		// Increment item quantity:
		$existing_order_item = $order->find_existing_order_item($item_id,$item_size);
		if ($existing_order_item > 0){
			$orderitem->get_by_id($existing_order_item);
			$old_quantity = $orderitem->get_quantity();
			$orderitem->set_quantity($old_quantity+$item_quantity);
			$orderitem->get_item_details();			
			$orderitem->set_price($orderitem->get_item_price());		
		} else {
		// No existing record of this item in this order, so continue populating object from posted variables:
			$orderitem->set_order_id($order->get_id());
			$orderitem->set_item_number($item_id);
			$orderitem->set_quantity($item_quantity);
			$orderitem->set_size($item_size);			
			$orderitem->get_item_details();
			if ($item_price > 0){
				$orderitem->set_price($item_price);
			} else {
				$orderitem->set_price($orderitem->get_item_price());
			}
			$orderitem->set_active("Y");
		}
		
		if($orderitem->save() == true) {
			// Success;
			$orderitem->set_success(true);
			$orderitem->setAlertMessage("Item added to order# " . $order->get_id());
		}
		else {
			//Not successful :(
			// The failure status and message will be set on the object level			
		}
	} // End add section

} else {
	$orderitem->set_success(false);
	$orderitem->setAlertMessage("Order id or order item id not set");
	exit();
}

// Return JSON at the end
/*echo "<pre>";
print_r($orderitem->get_json_data());
echo "</pre>";*/

// Recalculate order:
$order->get_by_id($order_id); // Yes, call this again; the currency can be updated when order item is added, and we don't want to overwrite it:
// If notifying customer that shipping value has been added:
if ($notify_customer){
	$order->notify_customer();
	// Change status from "waiting for shippinq quote" to "waiting for payment"
	$order->set_status(3);
}
$order->recalculate();
$order->save();

/*	$subtotal = $item_quantity * $orderitem->get_price();
			echo '<tr><td><a href="orderitem_edit.php?id=' . $orderitem->get_id() .'"><i class="fa fa-edit fa-lg"></i></a></td><td><a class="imagePreview" data-item-id="' . $orderitem->get_item_number() . '">' . $orderitem->get_item_name() . '</a></td><td>' . $orderitem->get_quantity() . '</td><td>' . $orderitem->get_size() . '</td><td style="white-space:normal; text-align:right;">$'.sprintf("%.2f",$orderitem->get_price()) .'</td><td style="white-space:normal; text-align:right ">$'.number_format(($orderitem->get_price()*$orderitem->get_quantity()),2) .'</td></tr>';*/
echo $orderitem->get_json_data();
			
//Important step so that the session message doesn't stay for the next screen refresh:
//$orderitem->alertClear();
?>