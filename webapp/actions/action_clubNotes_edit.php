<?php // include necessary libraries
require("../includes/init.php");
$page_id = $_REQUEST["page_id"];
$action = ($_GET['action'] != "delete" ? "edit" : "delete");
require(INCLUDES . "/acl_module.php");

$item_name = "clubNotes";

		$clubNotes_id=$_POST["clubNotes_id"];
		//$clubNotes_club_id=$_POST["clubNotes_club_id"];
		$clubNotes_content=$_POST["clubNotes_content"];
			// add the new record to the database
	include(CLASSES . "class_clubNotes.php");
	
		$clubNotes = new ClubNotes();
		$clubNotes->get_by_id($clubNotes_id);
	//	$clubNotes->set_club_id($clubNotes_club_id);
		$clubNotes->set_content($clubNotes_content);

if ($_GET['action'] == "delete"){	
	if($clubNotes->delete() == true) {
		$session->setAlertMessage("The $item_name has been removed successfully.");
		$session->setAlertColor("green");
			header("location:". BASE_URL."/club_edit.php?id=".$clubNotes->get_club_id());
		exit;
	}
	else {
		$session->setAlertMessage("There was a problem removing the $item_name. Please try again.");
		$session->setAlertColor("yellow");	
			header("location:". BASE_URL."/club_edit.php?id=".$clubNotes->get_club_id());
		exit;
	}

} else{

	if($clubNotes->save() == true) {
		//Check if new record
		if($clubNotes_id > 0){
			$session->setAlertMessage("The $item_name has been updated successfully.");
			$session->setAlertColor("green");
			header("location:". BASE_URL."/club_edit.php?id=".$clubNotes->get_club_id());
			exit;		
		}else{
			$session->setAlertMessage("The $item_name has been added successfully.");
			$session->setAlertColor("green");
			header("location:". BASE_URL."/club_edit.php?id=".$clubNotes->get_club_id());
			exit;
		}
	}
	else {
		$session->setAlertMessage("There was a problem adding/updating the $item_name. Please make sure all fields are correct.");
		$session->setAlertColor("yellow");
			header("location:". BASE_URL."/club_edit.php?id=".$clubNotes->get_club_id());
		exit;
	}	
}