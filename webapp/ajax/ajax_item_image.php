<?php
require("../includes/init.php"); 
require(CLASSES . "class_itemImage.php");
error_reporting(E_ALL);
ini_set('display_errors', '1');
//foreach ($_POST as $key => $val){
	//		echo $key . " - " . $val."<br>";
//	}
$action = escaped_var_from_post("action");
$item_id = escaped_var_from_post('item_id');
	
if ($item_id > 0 && $action == "add"){
	$itemImage_id = escaped_var_from_post('itemImage_id');	
	$itemImage_description = escaped_var_from_post('itemImage_description');

	if(isset($_POST['upload'])):
		include (CLASSES."class_file_upload.php"); //classes is the map where the class file is stored (one above the root)
		
		$max_size = 1024*1024; // the max. size for uploading 
			
		$my_upload = new file_upload;
		
		$my_upload->upload_dir = BASE ."/images/itemimages/"; // "files" is the folder for the uploaded files (you have to create this folder)
		$my_upload->extensions = array(".png", ".jpg", ".gif", ".jpeg"); // specify the allowed extensions here
		$my_upload->max_length_filename = 100; // change this value to fit your field length in your database (standard 100)
		$my_upload->rename_file = false;
		$my_upload->the_temp_file = $_FILES['upload']['tmp_name'];
		$my_upload->the_file = $_FILES['upload']['name'];
		$my_upload->http_error = $_FILES['upload']['error'];
		$my_upload->replace = (isset($_POST['replace'])) ? $_POST['replace'] : "n"; // because only a checked checkboxes is true
		$my_upload->do_filename_check = (isset($_POST['check'])) ? $_POST['check'] : "n"; // use this boolean to check for a valid filename
		$new_name = $_FILES['upload']['name'];
		
		if ($my_upload->upload()) { // new name is an additional filename information, use this to rename the uploaded file
			$full_path = $my_upload->upload_dir.$my_upload->file_copy;
			$info = $my_upload->get_uploaded_file_info($full_path);
			
			/////////// Create new record /////////////////
			$itemImage->set_item_id($item_id);	
			$itemImage->set_description($itemImage_description);					
			$itemImage->set_url($new_name);
			$itemImage->set_active("Y");
			
			if ($itemImage->save()):
			// Success;
			echo '<tr><td><a href="itemImage_edit.php?id=' . $itemImage->get_id() .'"><i class="fa fa-edit fa-lg"></i></a></td><td>' .$itemImage->get_description(). '</td><td>' . $itemImage->get_active() .'</td></tr>';			
			else:
				$session->setAlertMessage("Could not upload image: " . $my_upload->show_error_string());
				$session->setAlertColor("red");
			endif;
		}	
	endif;	
	echo "upload";
} elseif ($action == "delete"){
	$itemImage->get_by_id($itemImage_id);
	if($itemImage->delete() == true) {}
	else {
		$session->setAlertMessage("There was a problem removing the item. Please try again.");
		$session->setAlertColor("yellow");	
		header("location:".$_SERVER['HTTP_REFERER']);
		exit;
	}
} else {
	echo "<p>Item id not set</p>";
}
?>