<?php // include necessary libraries
require("../includes/init.php");
$page_id = $_REQUEST["page_id"];
$action = ($_GET['action'] != "delete" ? "edit" : "delete");
require(INCLUDES . "/acl_module.php");

$item_name = "brand";

		$brand_id=$_POST["brand_id"];
		$brand_name=$_POST["brand_name"];
		$brand_catalogue=$_POST["brand_catalogue"];
		$is_active=$_POST["is_active"];
			// add the new record to the database
	include(CLASSES . "class_brand.php");
	
		$brand = new Brand();
		$brand->get_by_id($brand_id);
		$brand->set_name($brand_name);
		$brand->set_catalogue($brand_catalogue);
		$brand->set_active($is_active);

if ($_GET['action'] == "delete"){	
	if($brand->delete() == true) {
		$session->setAlertMessage("The $item_name has been removed successfully.");
		$session->setAlertColor("green");
		header("location:".$_SERVER['HTTP_REFERER']);
		exit;
	}
	else {
		$session->setAlertMessage("There was a problem removing the $item_name. Please try again.");
		$session->setAlertColor("yellow");	
		header("location:".$_SERVER['HTTP_REFERER']);
		exit;
	}

} else{

	if($brand->save() == true) {
		//Check if new record
		if($brand_id > 0){
			$session->setAlertMessage("The $item_name has been updated successfully.");
			$session->setAlertColor("green");
			header("location:". BASE_URL."/" . $item_name . "_list.php?page=".$session->getPage());
			exit;		
		}else{
			$session->setAlertMessage("The $item_name has been added successfully.");
			$session->setAlertColor("green");
			header("location:". BASE_URL."/" . $item_name . "_edit.php?id=".$brand->get_id());
			exit;
		}
	}
	else {
		$session->setAlertMessage("There was a problem adding/updating the $item_name. Please make sure all fields are correct.");
		$session->setAlertColor("yellow");
		header("location:".$_SERVER['HTTP_REFERER']);
		exit;
	}	
}