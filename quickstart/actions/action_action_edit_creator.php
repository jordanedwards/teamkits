<?php
error_reporting(1);
ini_set('display_errors', '1');
extract($_GET);

$file = file_get_contents('../projects/'.$projectName, true);
$settings = json_decode($file, true);
if (!$settings['base_url'] == NULL){
$includes_url = "/" . $settings['base_url'];
}
if (!$settings['includes_url'] == NULL){
$includes_url .= "/" . $settings['includes_url'];
}

require_once('../classes/class_data_manager.php');
$dm = new DataManager($db_host,$db_user,$db_pass,$db_name);

	$sql = "Show columns from " . $selected_table;
	$result = $dm->queryRecords($sql);
	
	$num_rows = mysql_num_rows($result);
	
	function trim_from_marker($str, $marker) {
		$marker_location = strpos($str,$marker,0);
		return substr($str,$marker_location+1, strlen($str));
	}

	while ($row = mysql_fetch_row($result)) {
		$field_names[$row[0]]= trim_from_marker($row[0],"_");
	}


  header('Content-disposition: attachment; filename=action_'.$selected_table.'_edit.php');
  header ("Content-Type:text/php");  
  print("<?php");
?> // include necessary libraries
require("../includes/init.php");
$page_id = $_REQUEST["page_id"];
$action = ($_REQUEST['action'] != "delete" ? "edit" : "delete");
require(INCLUDES . "/acl_module.php");
require(CLASSES . "class_<?php echo $selected_table ?>.php");

$item_name = ucfirst("<?php echo $selected_table ?>");
$action=escaped_var_from_post('action');
$id = escaped_var_from_post('id');

$component = new <?php echo ucfirst($selected_table) ?>();

<?php
		/*
foreach($field_names as $key => $val){
	if ($val != "date_created" && $val != "last_updated"  && $val != "last_updated_user"){
		echo '$' . $key . '=$_POST["'. $key . '"];
		';
	}
}*/

/*foreach($field_names as $key => $val){
if ($val == "id"){
	echo '		if( _id >0){
	';
	echo '			$' . $selected_table . '->get_by_id($' .$key . ');
	';
	echo '		}
';
}elseif ($val != "date_created" && $val != "last_updated"  && $val != "last_updated_user"){
// Strip table name from the front of $row[0] when you get time
	echo '		$' . $selected_table . '->set_' . $val .'($' .$key . ');
';
}
}*/
?>
if ($id > 0){
	$component->get_by_id($id);
}
$component->load_from_post($_POST);
if (!isset($active)){
	$component->set_is_active("Y");
}
	
if ($action == "delete"){	
	if($component->delete() == true) {
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

} elseif($action == "add" || $action == "edit"){
	// add the new record to the database

	if($component->save() == true) {
		//Check if new record
		if($id > 0){
			$session->setAlertMessage("The $item_name has been updated successfully.");
			$session->setAlertColor("green");
			header("location:". BASE_URL."/" . strtolower($item_name) . "_list.php?page=".$session->getPage());
			exit;		
		}else{
			$session->setAlertMessage("The $item_name has been added successfully.");
			$session->setAlertColor("green");
			header("location:". BASE_URL."/" . strtolower($item_name) . "_edit.php?id=".$component->get_id());
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