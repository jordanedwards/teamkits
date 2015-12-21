<?php 
// Update items in a member's cart
$public=true;
require("../classes/class_cart.php");
require("../includes/init.php"); 

if (!isset($_SESSION['cart'])){
	$_SESSION['cart'] = new cart();
} 
	$cart = $_SESSION['cart'];
	
$action = escaped_var_from_post("action");
$name = escaped_var_from_post("name");
$qty = escaped_var_from_post("qty");
$size = escaped_var_from_post("size");

/*
$item_id = escaped_var_from_post("item_id");
$order_item_id = escaped_var_from_post("order_item_id");
$jersey_record_id = escaped_var_from_post("jersey_record_id");
*/

// Update size:
if ($action == "update_size"){
	$cart->edit_item($name,"size",$size);
}

// Update quantity:
if ($action == "update_qty"){
	$cart->edit_item($name,"qty",$qty);
}

?>