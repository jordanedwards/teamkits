<?php // include necessary libraries
require("../includes/init.php");
$page_id = $_REQUEST["page_id"];
$action = ($_REQUEST['action'] != "delete" ? "edit" : "delete");
if ($_REQUEST['delete'] == "Delete") { $action = "delete"; }
require(INCLUDES . "/acl_module.php");

$item_name = "Contact";

		$clubContact_id=$_POST["clubContact_id"];
		$clubContact_name=$_POST["clubContact_name"];
		$clubContact_position=$_POST["clubContact_position"];
		$clubContact_tel=$_POST["clubContact_tel"];
		$clubContact_email=$_POST["clubContact_email"];
		$is_active=$_POST["is_active"];
			// add the new record to the database
	include(CLASSES . "class_clubContact.php");
	
		$clubContact = new ClubContact();
		$clubContact->get_by_id($clubContact_id);
		$clubContact->set_name($clubContact_name);
		$clubContact->set_position($clubContact_position);
		$clubContact->set_tel($clubContact_tel);
		$clubContact->set_email($clubContact_email);
		$clubContact->set_active($is_active);

if ($action == "delete"){	
	if($clubContact->delete() == true) {
		$session->setAlertMessage("The $item_name has been removed successfully.");
		$session->setAlertColor("green");
			header("location: ../club_edit.php?id=".$clubContact->get_club_id());
		exit;
	}
	else {
		$session->setAlertMessage("There was a problem removing the $item_name. Please try again.");
		$session->setAlertColor("yellow");	
			header("location: ../club_edit.php?id=".$clubContact->get_club_id());
		exit;
	}

} else{

	if($clubContact->save() == true) {
		//Check if new record
		if($clubContact_id > 0){
			$session->setAlertMessage("The $item_name has been updated successfully.");
			$session->setAlertColor("green");
			header("location: ../club_edit.php?id=".$clubContact->get_club_id());
			exit;		
		}else{
			$session->setAlertMessage("The $item_name has been added successfully.");
			$session->setAlertColor("green");
			header("location: ../club_edit.php?id=".$clubContact->get_club_id());
			exit;
		}
	}
	else {
		$session->setAlertMessage("There was a problem adding/updating the $item_name. Please make sure all fields are correct.");
		$session->setAlertColor("yellow");
			header("location: ../club_edit.php?id=".$clubContact->get_id());
		exit;
	}	
}