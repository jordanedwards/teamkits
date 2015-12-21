<?php // include necessary libraries
require("../includes/init.php");
$page_id = $_REQUEST["page_id"];
$action=escaped_var_from_post('action');
require(INCLUDES . "/acl_module.php");

$item_name = ucfirst("brand");
$id = escaped_var_from_post('id');

<<<<<<< HEAD
$component = new Brand();

if ($id > 0){
	$component->get_by_id($id);
}
$component->load_from_post($_POST);

// If new logo uploaded:
if (isset($_FILES['logo']['name']) && $_FILES['logo']['name'] != ""):
	include_once (CLASSES."class_file_upload.php"); //classes is the map where the class file is stored (one above the root)
	
	$max_size = 1024*1024; // the max. size for uploading 
		
	$my_upload = new file_upload;
	$new_name = $_FILES['logo']['name'];
	$my_upload->upload_dir = $_SERVER['DOCUMENT_ROOT']."/img/brands/" ; // "files" is the folder for the uploaded files (you have to create this folder)
	$my_upload->extensions = array(".png", ".jpg", ".gif", ".jpeg"); // specify the allowed extensions here
	$my_upload->max_length_filename = 200; // change this value to fit your field length in your database (standard 100)
	$my_upload->rename_file = true;
	$my_upload->the_temp_file = $_FILES['logo']['tmp_name'];
	$my_upload->the_file = $_FILES['logo']['name'];
	$my_upload->http_error = $_FILES['logo']['error'];
	$my_upload->replace = "y"; // because only a checked checkboxes is true
	$my_upload->do_filename_check = (isset($_POST['check'])) ? $_POST['check'] : "n"; // use this boolean to check for a valid filename
	
	if ($my_upload->upload()) {
	 // new name is an additional filename information, use this to rename the uploaded file
		$full_path = $my_upload->upload_dir . $my_upload->file_copy;
		$info = $my_upload->get_uploaded_file_info($full_path);	
		$component->set_logo($my_upload->get_file_copy());
	}	else {
		echo "Unable to upload<br>";
	echo $my_upload->show_error_string()."<br>";
	echo $my_upload;
	exit();
	}
endif; // end new file upload

// If new main image uploaded:
if (isset($_FILES['main_image']['name']) && $_FILES['main_image']['name'] != ""):
	include_once (CLASSES."class_file_upload.php"); //classes is the map where the class file is stored (one above the root)
	
	$max_size = 1024*1024; // the max. size for uploading 
		
	$my_upload = new file_upload;
	$new_name = $_FILES['main_image']['name'];
	$my_upload->upload_dir = $_SERVER['DOCUMENT_ROOT']."/img/brands/" .strtolower($component->get_name())."/"; // "files" is the folder for the uploaded files (you have to create this folder)
	$my_upload->extensions = array(".png", ".jpg", ".gif", ".jpeg"); // specify the allowed extensions here
	$my_upload->max_length_filename = 200; // change this value to fit your field length in your database (standard 100)
	$my_upload->rename_file = true;
	$my_upload->the_temp_file = $_FILES['main_image']['tmp_name'];
	$my_upload->the_file = $_FILES['main_image']['name'];
	$my_upload->http_error = $_FILES['main_image']['error'];
	$my_upload->replace = "y"; // because only a checked checkboxes is true
	$my_upload->do_filename_check = (isset($_POST['check'])) ? $_POST['check'] : "n"; // use this boolean to check for a valid filename

	if ($my_upload->upload()) {
	 // new name is an additional filename information, use this to rename the uploaded file
		$full_path = $my_upload->upload_dir . $my_upload->file_copy;
		$info = $my_upload->get_uploaded_file_info($full_path);	
		$component->set_main_image($my_upload->get_file_copy());
	}	else {
		//unable to upload:
	echo "Unable to upload<br>";
	echo $my_upload->show_error_string()."<br>";
	exit();		
	
	}
endif; // end new file upload
/*
if (!isset($active)){
	$component->set_active("Y");
}*/

if ($action == "delete"){	
	if($component->delete() == true) {
=======
		$brand_id=$_POST["brand_id"];
		$brand_name=$_POST["brand_name"];
		$brand_currency=$_POST["brand_currency"];		
		$brand_catalogue=$_POST["brand_catalogue"];
		$is_active=$_POST["is_active"];
			// add the new record to the database
	include(CLASSES . "class_brand.php");
	
		$brand = new Brand();
		$brand->get_by_id($brand_id);
		$brand->set_name($brand_name);
		$brand->set_currency($brand_currency);		
		$brand->set_catalogue($brand_catalogue);
		$brand->set_active($is_active);

if ($_GET['action'] == "delete"){	
	if($brand->delete() == true) {
>>>>>>> f757d9f435864e736cc3bdfe6a140d905e3687d4
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
<<<<<<< HEAD
			header("location:". BASE_URL."/" . strtolower($item_name) . "_list.php?page=".$session->getPage());
=======
			header("location:". BASE_URL."/" . $item_name . "_list.php?page=".$session->getPage());
>>>>>>> f757d9f435864e736cc3bdfe6a140d905e3687d4
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