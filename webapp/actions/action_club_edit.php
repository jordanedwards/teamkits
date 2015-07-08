<?php // include necessary libraries
require("../includes/init.php");
$page_id = $_REQUEST["page_id"];
$action = ($_GET['action'] != "delete" ? "edit" : "delete");
require(INCLUDES . "/acl_module.php");
include_once(CLASSES . "class_user.php");

	$club_id=$_POST["club_id"];
	$club_name=$_POST["club_name"];
	$club_sport=$_POST["club_sport"];
	$club_brand=$_POST["club_brand"];
	$club_tel=$_POST["club_tel"];
	$club_address=$_POST["club_address"];
	$club_city=$_POST["club_city"];
	$club_province=$_POST["club_province"];
	$club_country=$_POST["club_country"];
	$club_postal_code=$_POST["club_postal_code"];
	$club_email=$_POST["club_email"];
	$club_code=$_POST["club_code"];
	$club_account_type=$_POST["club_account_type"];
	$club_tax_id=$_POST["club_tax_id"];
	$is_active=$_POST["is_active"];
	
	// User vars:
	$user_login = $_POST['user_login'];
	$user_password = $_POST['user_password'];
	$user_active = $_POST['user_active'];
	
	// Populate club object
	include(CLASSES . "class_club.php");

	$club = new Club();
	if($club_id >0){
		$club->get_by_id($club_id);
	}
	$club->set_name($club_name);
	$club->set_sport($club_sport);
	$club->set_brand($club_brand);
	$club->set_tel($club_tel);
	$club->set_address($club_address);
	$club->set_city($club_city);
	$club->set_province($club_province);
	$club->set_country($club_country);
	$club->set_postal_code($club_postal_code);
	$club->set_email($club_email);
	$club->set_code($club_code);
	$club->set_account_type($club_account_type);
	$club->set_tax_id($club_tax_id);
	$club->set_active($is_active);
	
	// Populate user object:
	if ($club_account_type != 3){
	
  	$club_user = new User;
	if($club->get_user_id() >0){
		$club_user->get_by_id($club->get_user_id());
	}
		
	if ($user_password != ""){
		$club_user->set_password($user_password);
	}
	$club_user->set_first_name($club_name);
	$club_user->set_email($user_login);
	$club_user->set_active($user_active);
	$club_user->set_role("3");
	$club_user->save();
	
	$club->set_user_id($club_user->get_id());
	}
	
  	$last_updated_user = new User;	
  	$last_updated_user->get_by_id($session->get_user_id());
	$club->set_last_updated_user($last_updated_user->get_first_name().' '.$last_updated_user->get_last_name());

if ($_GET['action'] == "delete"){
	$dm = new DataManager();
	$id = mysqli_real_escape_string($dm->connection, $_GET['id']);
	
	if($club->delete_by_id($id) == true) {
		$session->setAlertMessage("The Club has been removed successfully.");
		$session->setAlertColor("green");
		header("location:".$_SERVER['HTTP_REFERER']);
		exit;
	}
	else {
		$session->setAlertMessage("There was a problem removing the Club. Please try again.");
		$session->setAlertColor("yellow");	
		header("location:".$_SERVER['HTTP_REFERER']);
		exit;
	}

} else{
	if($club->save() == true) {
		//Check if new record
		if($club_id > 0){
			$session->setAlertMessage("The Club has been updated successfully.");
			$session->setAlertColor("green");
			if ($club_account_type == 3){
				header("location:". BASE_URL."/" . "lead_list.php?page=".$session->getPage());
			} else {
				header("location:". BASE_URL."/" . "club_list.php?page=".$session->getPage());
			}
			exit;		
		}else{
			$session->setAlertMessage("The Club has been added successfully.");
			$session->setAlertColor("green");
			if ($club_account_type == 3){
				header("location:". BASE_URL."/" . "lead_list.php?page=".$session->getPage());
			} else {
				header("location:". BASE_URL."/" . "club_list.php?page=".$session->getPage());
			}
			exit;
		}
	}
	else {
		$session->setAlertMessage("There was a problem adding/updating the Club. Please make sure all fields are correct.");
		$session->setAlertColor("yellow");
		header("location:".$_SERVER['HTTP_REFERER']);
		exit;
	}	
} ?>