<?php // include necessary libraries
require("../includes/init.php");
$page_id = $_REQUEST["page_id"];
$action = ($_GET['action'] != "delete" ? "edit" : "delete");
require(INCLUDES . "/acl_module.php");

	$promo_id=$_POST["promo_id"];
	$promo_sport=$_POST["promo_sport"];
	$promo_title=$_POST["promo_title"];
	$promo_description=$_POST["promo_description"];
	$promo_view_list=$_POST["promo_view_list"];
	$promo_wholesale=$_POST["promo_wholesale"];
	$promo_price=$_POST["promo_price"];
	$promo_image=$_POST["promo_image"];
	$promo_tax=$_POST["promo_tax"];
	$is_active=$_POST["is_active"];
		// add the new record to the database
	include(CLASSES . "class_promo.php");
	
		$promo = new Promo();
		$promo->set_id($promo_id);
		$promo->set_sport($promo_sport);
		$promo->set_title($promo_title);
		$promo->set_description($promo_description);
		$promo->set_view_list($promo_view_list);
		$promo->set_wholesale($promo_wholesale);
		$promo->set_price($promo_price);
		$promo->set_image($promo_image);
		$promo->set_tax($promo_tax);
		$promo->set_active($is_active);

	include_once(CLASSES . "class_user.php");
  	$last_updated_user = new User;
  	$last_updated_user->get_by_id($session->get_user_id());
	$promo->set_last_updated_user($last_updated_user->get_first_name().' '.$last_updated_user->get_last_name());

if ($_GET['action'] == "delete"){
	$dm = new DataManager();
	$id = mysqli_real_escape_string($dm->connection, $_GET['id']);
	
if($promo->delete_by_id($id) == true) {
		$session->setAlertMessage("The Promo has been removed successfully.");
		$session->setAlertColor("green");
		header("location:".$_SERVER['HTTP_REFERER']);
		exit;
	}
	else {
		$session->setAlertMessage("There was a problem removing the Promo. Please try again.");
		$session->setAlertColor("yellow");	
		header("location:".$_SERVER['HTTP_REFERER']);
		exit;
	}

} else{

	if($promo->save() == true) {
		//Check if new record
		if($promo_id > 0){
			$session->setAlertMessage("The Promo has been updated successfully.");
			$session->setAlertColor("green");
			header("location:". BASE_URL."/" . "promo_list.php?page=".$session->getPage());
			exit;		
		}else{
			$session->setAlertMessage("The Promo has been added successfully.");
			$session->setAlertColor("green");
			header("location:". BASE_URL."/" . "promo_edit.php?id=".$promo->get_id());
			exit;
		}
	}
	else {
		$session->setAlertMessage("There was a problem adding/updating the Promo. Please make sure all fields are correct.");
		$session->setAlertColor("yellow");
		header("location:".$_SERVER['HTTP_REFERER']);
		exit;
	}	
}