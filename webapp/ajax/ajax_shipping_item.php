<?php
require("../includes/init.php"); 
require(CLASSES . "class_shippingrecord.php");
error_reporting(E_ALL);
ini_set('display_errors', '1');

//Either the order id has to be set (adding an item), or the order item id (editing/deleting an item)
if (isset($_GET['order_id'])){
	$action = escaped_var_from_post("action");
	$order_id = escaped_var_from_post('order_id');
	$shippingrecord_carrier = escaped_var_from_post('shippingrecord_carrier');
	$shippingrecord_tracking = escaped_var_from_post("shippingrecord_tracking");
	$shippingrecord_date = escaped_var_from_post("shippingrecord_date");

	if ($action == "delete"):
		$shippingrecord->get_by_id($item_id);
		
		if($shippingrecord->delete() == true) {}
		else {
			$session->setAlertMessage("There was a problem removing the order item. Please try again.");
			$session->setAlertColor("yellow");	
			header("location:".$_SERVER['HTTP_REFERER']);
			exit;
		}
	endif;
	if ($action == "add"):
		$shippingrecord= new Shippingrecord();	
		$shippingrecord->set_order_id($order_id);
		$shippingrecord->set_carrier($shippingrecord_carrier);
		$shippingrecord->set_tracking($shippingrecord_tracking);
		$shippingrecord->set_date($shippingrecord_date);
		$shippingrecord->set_active("Y");
		$shippingrecord->set_last_updated_user();
		
		if($shippingrecord->save() == true) {
			// Success;
			echo '<tr><td><a href="shippingrecord_edit.php?id=' . $shippingrecord->get_id() .'"><i class="fa fa-edit fa-lg"></i></a></td><td>' .$shippingrecord->get_date(). '</td><td>' . $shippingrecord->get_carrier() .'</td><td>' . $shippingrecord->get_tracking().'</td></tr>';
		}
		else {
			exit("unable to save");
		}	
	endif;
} else {
	echo "<p>Order id not set</p>";
}
?>