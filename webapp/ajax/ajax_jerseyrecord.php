<?php 
require_once("../includes/init.php"); 
//require(CLASSES . "class_orderitem.php");
require(CLASSES . "class_jerseyRecord.php");
//require_once(CLASSES . "/class_user.php"); 
//require_once(CLASSES . "/class_club.php");
 
//$club = new Club();	 
//$club->get_by_user_id($session->get_user_id()); 
//$club_id = $club->get_id();

//$orderitem = new Orderitem();
$jerseyRecord= new JerseyRecord();

$action = escaped_var_from_post("action");

//Either the order id has to be set (adding an item), or the order item id (editing/deleting an item)
if (isset($_REQUEST['orderitem_id']) || isset($_REQUEST['id'])){
	$id = escaped_var_from_post('id');
	$orderitem_id = escaped_var_from_post('orderitem_id');
	$number = escaped_var_from_post("number");
	$name = escaped_var_from_post("name");

// DELETING AN ITEM
	if ($action == "delete"):
		$jerseyRecord->get_by_id($id);	
		if($jerseyRecord->delete() == true) {
			$jerseyRecord->set_success(true);
			$jerseyRecord->setAlertMessage("Item deleted");
		}
		else {
			$jerseyRecord->set_success(false);
			$jerseyRecord->setAlertMessage("There was a problem removing the order item. Please try again.");
		}
	endif;
	
// ADDING A NEW ITEM
	if ($action == "add"){
			$jerseyRecord->set_orderitem_id($orderitem_id);
			$jerseyRecord->set_number($number);
			$jerseyRecord->set_name($name);
			$jerseyRecord->set_is_active("Y");
		
		if($jerseyRecord->save() == true) {
			// Success;
			$jerseyRecord->set_success(true);
			$jerseyRecord->setAlertMessage("Jersey number added");
		}
		else {
			// Not successful :(			
			$jerseyRecord->set_success(false);
			$jerseyRecord->setAlertMessage("Unable to save - check debug log");	
		}
	} // End add section

} else {
	$jerseyRecord->set_success(false);
	$jerseyRecord->setAlertMessage("Order item id or id not set");
}

// Return JSON at the end
/*echo "<pre>";
print_r($jerseyRecord->get_json_data());
echo "</pre>";*/

echo $jerseyRecord->get_json_data();
//Important step so that the session message doesn't stay for the next screen refresh:
$jerseyRecord->alertClear();
?>