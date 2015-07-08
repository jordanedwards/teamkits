<?php /*
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors',1);
ini_set('log_errors',1);
ini_set('log_errors_max_len',0);
ini_set('error_log','error_log.txt');
*/

//Need to figure out how to use ACL with ajax, as the page id gets pretty abstract as this can be accessed from any page.
require("../includes/init.php"); 
require(CLASSES . "class_orderitem.php");
require(CLASSES . "class_orders.php");
require_once(CLASSES . "/class_user.php"); 
require_once(CLASSES . "/class_club.php");
 
$club = new Club();	 
$club->get_by_user_id($session->get_user_id()); 
$club_id = $club->get_id();

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
		if (!$order_id > 0){
			// No order id given, so start by looking up any open orders: (function loads order if found)
			if(!$order->get_open_order($club_id)){
				// No open order exists, so create a new one:
				$order->set_club_id($club_id);
				$order->set_status(1);
				$order->set_active("Y");				
				$order->save();
			}
		} else {
			// order id is given in POST, so load it:
			$order->get_by_id($order_id);
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
			$orderitem->set_price($orderitem->get_item_price());
			$orderitem->set_active("Y");
		}
		
		if($orderitem->save() == true) {
			// Success;
			$orderitem->set_success(true);
			$orderitem->setAlertMessage("Promo item added to order# " . $order->get_id());
		}
		else {
			//Not successful :(
			$orderitem->set_success(false);
			$orderitem->setAlertMessage("Unable to save - check debug log");
			
		}
	} // End add section

} else {
	$orderitem->set_success(false);
	$orderitem->setAlertMessage("Order id or order item id not set");
}

// Return JSON at the end
/*echo "<pre>";
print_r($orderitem->get_json_data());
echo "</pre>";*/

// Recalculate order:
$order->recalculate();
$order->save();

echo $orderitem->get_json_data();
//Important step so that the session message doesn't stay for the next screen refresh:
$orderitem->alertClear();
?>