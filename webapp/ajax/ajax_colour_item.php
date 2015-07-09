<?php
require("../includes/init.php"); 
require(CLASSES . "class_clubColours.php");
$clubColour = new ClubColours();

error_reporting(E_ALL);
ini_set('display_errors', '1');

//Either the order id has to be set (adding an item), or the order item id (editing/deleting an item)
if (isset($_GET['club_id'])){
	
	$action = escaped_var_from_post("action");
	$club_colour_id = escaped_var_from_post("colour_id");
	$club_id=escaped_var_from_post("club_id");
	$clubColour->load_from_post($_GET);
	$clubColour->set_active("Y");

	if ($action == "delete"):
		$clubColour->get_by_id($item_id);
		
		if($clubColour->delete() == true) {}
		else {
			$session->setAlertMessage("There was a problem removing the item. Please try again.");
			$session->setAlertColor("yellow");	
			header("location:".$_SERVER['HTTP_REFERER']);
			exit;
		}
	endif;
	if ($action == "add"):
		
		if($clubColour->save() == true) {
			// Success;
			echo '<tr><td><a href="clubColours_edit.php?id=' . $clubColour->get_id() .'"><i class="fa fa-edit fa-lg"></i></a></td><td>' .$clubColour->get_title(). '</td><td>' . $clubColour->get_code() .'</td><td style="background:' . $clubColour->get_hex_code().'"></td></tr>';
		}
		else {
			echo("unable to save");
		}	
	endif;
} else {
	echo "<p>Club id not set</p>";
}
?>