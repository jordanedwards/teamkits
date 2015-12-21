<?php 
$public=true;
require("../classes/class_cart.php");
require("../includes/init.php"); 
$activeMenuItem = "Cart";

require_once(CLASSES . "class_user.php"); 	
require_once(CLASSES . "/class_club.php"); 
$club = new Club();	 
$club->get_by_id($session->get_club()); 

if (!$club->get_id() >0){
	$session->setAlertColor("red");			
	$session->setAlertMessage("Please login");
	header("location: /login.php");		
}

if (!isset($_SESSION['cart'])){
	$_SESSION['cart'] = new cart();
} 
	$cart = $_SESSION['cart'];
	
$action = escaped_var_from_post("action");
$item_id = escaped_var_from_post("item_id");
$name = escaped_var_from_post("name");
$order_item_id = escaped_var_from_post("order_item_id");
$jersey_record_id = escaped_var_from_post("jersey_record_id");
$item_quantity = escaped_var_from_post("item_quantity");
if ($item_quantity == 0){$item_quantity =1;}
$item_size = escaped_var_from_post("item_size");

//look up item price and name:
if ($item_id > 0 ){
	$dm = new DataManager(); 
	$strSQL = "SELECT *
	FROM item 
	WHERE item_id=" . $item_id;						

	$result = $dm->queryRecords($strSQL);	
	if ($result){
		while($row = mysqli_fetch_assoc($result)):
			$item_price = $row['item_price'];
			$item_name = $row['item_name'];
		endwhile;						
	}
}

if ($jersey_record_id > 0){
	if (isset($_REQUEST['action'])){	
		switch ($action):
			case "add":
				$cart->add_jersey_item($jersey_record_id);
			break;
			case "delete":
				$cart->delete_item($name, 1);
			break;
		endswitch;
	}
} else {
	if (isset($_REQUEST['action'])){	
		switch ($action):
			case "add":
				$cart->add_item($item_name, $item_id, $item_price, $item_quantity, $item_size, $order_item_id);
			break;
			case "delete":
				$cart->delete_item($name, 1);
			break;
		endswitch;
	}
}
?>