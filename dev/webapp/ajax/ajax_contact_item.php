<?php
require("../includes/init.php"); 
require(CLASSES . "class_clubContact.php");
error_reporting(E_ALL);
ini_set('display_errors', '1');

//Either the order id has to be set (adding an item), or the order item id (editing/deleting an item)
if (isset($_GET['club_id'])){
	
	$action = escaped_var_from_post("action");
	$club_id=escaped_var_from_post("club_id");
/*	$clubContact_id=escaped_var_from_post("clubContact_id");	
	$clubContact_name=escaped_var_from_post("clubContact_name");
	$clubContact_position=escaped_var_from_post("clubContact_position");
	$clubContact_tel=escaped_var_from_post("clubContact_tel");
	$clubContact_email=escaped_var_from_post("clubContact_email");*/

	if ($action == "delete"):
		$payment->get_by_id($item_id);
		
		if($payment->delete() == true) {}
		else {
			$session->setAlertMessage("There was a problem removing the item. Please try again.");
			$session->setAlertColor("red");	
			header("location:".$_SERVER['HTTP_REFERER']);
			exit;
		}
	endif;
	if ($action == "add"):
		$contact = new ClubContact();
		$contact->load_from_post($_GET);
		
		if ($clubContact_id > 0){
			$contact->get_by_id($clubContact_id);
		}
		$contact->set_active("Y");
		
		if($contact->save() == true) {
			// Success;
			echo '<tr><td><a href="clubContact_edit.php?id=' . $contact->get_id() .'"><i class="fa fa-edit fa-lg"></i></a></td><td>' .$contact->get_name(). '</td><td>' .$contact->get_tel(). '</td><td>' .$contact->get_email(). '</td></tr>';
		}
		else {
			echo("unable to save");
		}	
	endif;
} else {
	echo "<p>Club id not set</p>";
}
?>