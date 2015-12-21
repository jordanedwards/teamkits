<?php // include necessary libraries
require("../includes/init.php");
$page_id = $_REQUEST["page_id"];
$action = ($_REQUEST['action'] != "delete" ? "edit" : "delete");
require(INCLUDES . "/acl_module.php");
require(CLASSES . "class_brandcatalogue.php");

$item_name = ucfirst("brandcatalogue");
$action=escaped_var_from_post('action');
$id = escaped_var_from_post('id');

$component = new Brandcatalogue();
if ($id > 0){
	$component->get_by_id($id);
}
$component->load_from_post($_POST);

// If new pic uploaded:
if (isset($_FILES['upload']['name'])):

	include (CLASSES."class_file_upload.php"); //classes is the map where the class file is stored (one above the root)
	
	$max_size = 1024*1024; // the max. size for uploading 
		
	$my_upload = new file_upload;
	$new_name = $_FILES['upload']['name'];
	$my_upload->upload_dir = $_SERVER['DOCUMENT_ROOT']."/img/brands/" .strtolower($component->get_brand_name())."/"; // "files" is the folder for the uploaded files (you have to create this folder)
	$my_upload->extensions = array(".png", ".jpg", ".gif", ".jpeg"); // specify the allowed extensions here
	$my_upload->max_length_filename = 200; // change this value to fit your field length in your database (standard 100)
	$my_upload->rename_file = true;
	$my_upload->the_temp_file = $_FILES['upload']['tmp_name'];
	$my_upload->the_file = $_FILES['upload']['name'];
	$my_upload->http_error = $_FILES['upload']['error'];
	$my_upload->replace = (isset($_POST['replace'])) ? $_POST['replace'] : "n"; // because only a checked checkboxes is true
	$my_upload->do_filename_check = (isset($_POST['check'])) ? $_POST['check'] : "n"; // use this boolean to check for a valid filename
	
	if ($my_upload->upload()) {
		//echo $my_upload;
	 // new name is an additional filename information, use this to rename the uploaded file
		$full_path = $my_upload->upload_dir . $my_upload->file_copy;
		$info = $my_upload->get_uploaded_file_info($full_path);
//		$_FILES['upload']['name']
		$component->set_image($my_upload->get_file_copy());
	}	

endif; // end new file upload

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
			header("location:". BASE_URL."/brand_edit.php?id=".$component->get_brand_id());
			exit;		
		}else{
			$session->setAlertMessage("The $item_name has been added successfully.");
			$session->setAlertColor("green");
			header("location:". BASE_URL."/brand_edit.php?id=".$component->get_brand_id());
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
?>