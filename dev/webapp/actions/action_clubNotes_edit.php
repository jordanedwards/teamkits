<?php // include necessary libraries
require("../includes/init.php");
$page_id = $_REQUEST["page_id"];
$action = ($_GET['action'] != "delete" ? "edit" : "delete");
if ($_REQUEST['delete'] == "Delete") { $action = "delete"; }

require(INCLUDES . "/acl_module.php");
include(CLASSES . "class_clubNotes.php");
$id = escaped_var_from_post("id");
$item_name = "clubNotes";

	$clubNotes = new ClubNotes();
	$clubNotes->get_by_id($id);
	$clubNotes->load_from_post($_POST);

if ($action == "delete"){	
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
		if($id > 0){
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