<?php
require("../includes/init.php"); 

if (isset($_REQUEST['item_id'])){
	$item_id = escaped_var_from_post("item_id");
	
	$dd = New DropDown();
	$dd->set_table("itemSize");
	$dd->set_name("item_size");		
	$dd->set_name_field("itemSize_name");
	$dd->set_class_name("form-control inline");
	$dd->set_index_name("itemSize_name");
	$dd->set_where("itemSize_item_id = ".$item_id);		
	$dd->set_order("ASC");						
	$dd->set_required(true);
	$dd->set_active_only(true);
	$dd->set_placeholder("Select size");		
	$dd->display();
}
?>