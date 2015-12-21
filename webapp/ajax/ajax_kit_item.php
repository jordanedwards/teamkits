<?php
require("../includes/init.php"); 
require(CLASSES . "class_item.php");
require(CLASSES . "class_kitItem.php");
error_reporting(E_ALL);
ini_set('display_errors', '1');

//Either the order id has to be set (adding an item), or the order item id (editing/deleting an item)
if (isset($_GET['kit_id']) || isset($_GET['kit_item_id'])){
	extract_and_escape($_GET);

//echo "item: " . $item_id;
	$item = new kitItem();	

if ($action == "delete"):
		$item->get_by_id($item_id);
		
		if($item->delete() == true) {}
		else {
			$session->setAlertMessage("There was a problem removing theitem. Please try again.");
			$session->setAlertColor("yellow");	
			header("location:".$_SERVER['HTTP_REFERER']);
			exit;
		}
elseif ($action == "add"):

		$item->set_build_id($kit_id);
		$item->set_item_id($item_id);
		$item->set_is_active("Y");
		//echo $item;
		if($item->save() == true) {
			// Success;
			echo '<tr><td data-item-id="' . $item->get_id() . '" class="item_view_link">' . $item->get_name() .'</td><td>$' .number_format($item->get_price(),2). '</td><td><a href="actions/action_kitItem_edit.php?action=delete&page_id=kitItem_edit.php&id=' . $item->get_id() . '" onclick="return confirm(\'You are about to delete an item. Do you want to continue?\');"><i class="fa fa-times-circle fa-lg"></i></a></td></tr>';
		}
		else {
			exit("unable to save");
		}	
endif;

} else {
	echo "<p>Kit id or item id not set</p>";
}
?>