<?php
require("../includes/init.php"); 
require(CLASSES . "class_itemSize.php");
// required fields: item_id & $action

if (isset($_GET['item_id']) || isset($_GET['item_size_id'])){
	$item_id = escaped_var_from_post('item_id');
	$item_size_id = escaped_var_from_post('item_size_id');	
	$stock = escaped_var_from_post('stock');
	$size = escaped_var_from_post('size');	
	$action = escaped_var_from_post("action");

	$itemSize = new ItemSize();	

	if ($action == "delete"):
		$itemSize->get_by_id($item_size_id);
		if($itemSize->delete() == true) {}
		else {
			$session->setAlertMessage("There was a problem removing the order item. Please try again.");
			$session->setAlertColor("yellow");	
			header("location:".$_SERVER['HTTP_REFERER']);
			exit;
		}
	endif;
	if ($action == "add"):
		$itemSize->set_item_id($item_id);
		$itemSize->set_stock($stock);
		$itemSize->set_name($size);		
		$itemSize->set_active("Y");
		
		if($itemSize->save() == true) {
			// Success;						
		echo '<tr><td><a href="itemSize_edit.php?id=' . $itemSize->get_id() .'"><i class="fa fa-edit fa-lg"></i></a></td><td>' . $itemSize->get_name() . '</td><td>' . $itemSize->get_stock() . '</td><td style="white-space:normal"><a href="actions/action_itemSize_edit.php?action=delete&page_id=item_edit.php&itemSize_item_id=' . $itemSize->get_item_id(). '&itemSize_id=' . $itemSize->get_id() .'" onclick="return confirm(\'You are about to delete a size. Do you want to continue?\');"><i class="fa fa-times-circle fa-lg"></i></a></td></tr>';			
		}
		else {
			exit("unable to save");
		}	
	endif;

} else {
	echo "<p>Item id OR Item size id not set</p>";
}
?>