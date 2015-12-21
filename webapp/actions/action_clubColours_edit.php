<?php // include necessary libraries
require("../includes/init.php");
$page_id = $_REQUEST["page_id"];
$action = ($_GET['action'] != "delete" ? "edit" : "delete");
require(INCLUDES . "/acl_module.php");

$item_name = "Club Colours";

		$clubColours_id=$_POST["clubColours_id"];
	//	$clubColours_club_id=$_POST["clubColours_club_id"];
		$clubColours_code=$_POST["clubColours_code"];
		$clubColours_hex_code=$_POST["clubColours_hex_code"];
		$clubColours_title=$_POST["clubColours_title"];
		//$is_active=$_POST["is_active"];
		// add the new record to the database
		include(CLASSES . "class_clubColours.php");
	
		$clubColours = new ClubColours();
		$clubColours->get_by_id($clubColours_id);
		//$clubColours->set_club_id($clubColours_club_id);
		$clubColours->set_code($clubColours_code);
		$clubColours->set_hex_code($clubColours_hex_code);
		$clubColours->set_title($clubColours_title);
	//	$clubColours->set_active($is_active);

if ($_GET['action'] == "delete"){	
	if($clubColours->delete() == true) {
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

	if($clubColours->save() == true) {
		//Check if new record
		if($clubColours_id > 0){
			$session->setAlertMessage("The $item_name has been updated successfully.");
			$session->setAlertColor("green");
			header("location:".$_SERVER['HTTP_REFERER']);
			exit;		
		}else{
			$session->setAlertMessage("The $item_name has been added successfully.");
			$session->setAlertColor("green");
			header("location:".$_SERVER['HTTP_REFERER']);
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