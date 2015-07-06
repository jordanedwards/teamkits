<?php // include necessary libraries
require("../includes/init.php");
$page_id = $_REQUEST["page_id"];
$action = ($_GET['action'] != "delete" ? "edit" : "delete");
require(INCLUDES . "/acl_module.php");

$item_name = "clubUploads";

		$clubUploads_id=$_POST["clubUploads_id"];
		$clubUploads_club_id=$_POST["clubUploads_club_id"];
		$clubUploads_title=$_POST["clubUploads_title"];
		$clubUploads_doc_name=$_POST["clubUploads_doc_name"];
		$clubUploads_note=$_POST["clubUploads_note"];
		$is_active=$_POST["is_active"];
			// add the new record to the database
	include(CLASSES . "class_clubUploads.php");
	
		$clubUploads = new ClubUploads();
		$clubUploads->get_by_id($clubUploads_id);
		$clubUploads->set_club_id($clubUploads_club_id);
		$clubUploads->set_title($clubUploads_title);
		$clubUploads->set_doc_name($clubUploads_doc_name);
		$clubUploads->set_note($clubUploads_note);
		$clubUploads->set_active($is_active);

if ($_GET['action'] == "delete"){	
	if($clubUploads->delete() == true) {
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

	if($clubUploads->save() == true) {
		//Check if new record
		if($clubUploads_id > 0){
			$session->setAlertMessage("The $item_name has been updated successfully.");
			$session->setAlertColor("green");
			header("location:". BASE_URL."/" . $item_name . "_list.php?page=".$session->getPage());
			exit;		
		}else{
			$session->setAlertMessage("The $item_name has been added successfully.");
			$session->setAlertColor("green");
			header("location:". BASE_URL."/" . $item_name . "_edit.php?id=".$clubUploads->get_id());
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