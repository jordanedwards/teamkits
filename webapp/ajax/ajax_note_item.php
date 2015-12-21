<?php
require("../includes/init.php"); 
require(CLASSES . "class_clubNotes.php");
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

if (isset($_GET['club_id'])){
	$club_id = escaped_var_from_post('club_id');
	$club_note = escaped_var_from_post('clubNote');
	$followup_date = escaped_var_from_post('followup_date');
	$action = escaped_var_from_post("action");

	$clubNote = new clubNotes();	

	include_once(CLASSES . "class_user.php");
  	$last_updated_user = new User;
  	$last_updated_user->get_by_id($session->get_user_id());
	$clubNote->set_last_updated_user($last_updated_user->get_first_name().' '.$last_updated_user->get_last_name());

	if ($action == "delete"):
		$clubNote->get_by_id($item_id);
		
		if($clubNote->delete() == true) {}
		else {
			$session->setAlertMessage("There was a problem removing the order item. Please try again.");
			$session->setAlertColor("yellow");	
			header("location:".$_SERVER['HTTP_REFERER']);
			exit;
		}
	endif;
	if ($action == "add"):
		$clubNote->set_club_id($club_id);
		$clubNote->set_content($club_note);
		$clubNote->set_followup_date($followup_date);		
		$clubNote->set_active("Y");
		
		if($clubNote->save() == true) {
			// Success;
			echo '<tr><td><a href="clubNotes_edit.php?id=' . $clubNote->get_id() .'"><i class="fa fa-edit fa-lg"></i></a></td><td>' . $clubNote->get_content() .'</td><td>' .substr($clubNote->get_date_created(),0,10). '</td><td>' . $clubNote->get_followup_date() .'</td><td>N</td></tr>';
		}
		else {
			exit("unable to save");
		}	
	endif;

} else {
	echo "<p>Club id not set</p>";
}
?>