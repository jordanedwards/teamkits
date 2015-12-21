<?php 
require("includes/init.php"); 
$page_id = basename(__FILE__);
require(INCLUDES . "/acl_module.php");
include(CLASSES . "/class_promo.php"); 
 
	if(!isset($_GET["id"])) {
		$session->setAlertMessage("Can not edit - the ID is invalid. Please try again.");
		$session->setAlertColor("yellow");
		header("location:" . BASE_URL . "/index.php");
		exit;
	}

	// load the promo		
	$promo_id = $_GET["id"];
	$promo = new Promo();
	
	if ($_GET["id"] ==0){
		// Change this to pass a parent value if creating a new record:
		//	$promo->set_customer_id($_GET['customer_id']);
	} else {
		$promo->get_by_id($promo_id);
	}
	
$activeMenuItem = "Promo";

if (isset($_POST['add_image']) && isset($_FILES['upload']['name'])):
	include (CLASSES."class_file_upload.php"); //classes is the map where the class file is stored (one above the root)
	
	$max_size = 1024*1024; // the max. size for uploading 
		
	$my_upload = new file_upload;
	$new_name = $_FILES['upload']['name'];
	$my_upload->upload_dir = BASE ."/images/promo/"; // "files" is the folder for the uploaded files (you have to create this folder)
	$my_upload->extensions = array(".png", ".jpg", ".gif", ".jpeg"); // specify the allowed extensions here
	$my_upload->max_length_filename = 100; // change this value to fit your field length in your database (standard 100)
	$my_upload->rename_file = true;
	$my_upload->the_temp_file = $_FILES['upload']['tmp_name'];
	$my_upload->the_file = $_FILES['upload']['name'];
	$my_upload->http_error = $_FILES['upload']['error'];
	$my_upload->replace = (isset($_POST['replace'])) ? $_POST['replace'] : "n"; // because only a checked checkboxes is true
	$my_upload->do_filename_check = (isset($_POST['check'])) ? $_POST['check'] : "n"; // use this boolean to check for a valid filename
	
	//echo $my_upload;
	
	if ($my_upload->upload()) {
		//echo $my_upload;
	 // new name is an additional filename information, use this to rename the uploaded file
		$full_path = $my_upload->upload_dir.$my_upload->file_copy;
		$info = $my_upload->get_uploaded_file_info($full_path);
		
		//$promo->get_by_id($promo_id);
		$promo->set_image($my_upload->get_file_copy());
		$promo->save();
		
		// Success;
		$session->setAlertMessage("Image uploaded successfully");
		$session->setAlertColor("green");					
	} else {
		$session->setAlertMessage("Could not upload image: " . $my_upload->show_error_string());
		$session->setAlertColor("red");	
	}
endif;
 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
	<?php  include(HEAD);  ?>
    <meta name="description" content="">

    <title><?php   echo $appConfig["app_title"];  ?> | Promo Edit</title>
  </head>

  <body>

<?php  require(INCLUDES . "navbar.php");  ?>
<div class="main">

    <div class="container">
      <div class="row">
        <div class="col-md-12">
        <?php  include(INCLUDES . "system_messaging.php");  ?>

        <h1><?php if ($_GET["id"] ==0){ ?> Add Promo<?php  } else { ?> Edit Promo<?php  } ?></h1>
        <p><span class="red">*</span> The red asterisk indicates all mandatory fields.</p>
        <div class="errorContainer">
          <p><strong>There are errors in your form submission. Please read below for details.</strong></p>
          <ol>
		            </ol>
		  <br>
        </div>
		</div>
	</div>
	
	<div class="row">
	<div class="col-md-6">
	<form id="form_promo" action="<?php  echo ACTIONS_URL; ?>action_promo_edit.php" method="post">
	<input type="hidden" name="promo_id" value="<?php  echo $promo->get_id();  ?>" />
	<input type="hidden" name="action" value="edit" />	
	<input type="hidden" name="page_id" value="<?php  echo $page_id  ?>" />	
	
         <table class="admin_table">
		 	<tr><th colspan="2">Promo Details</th></tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Sport: </td>
				
					<td>
					<?php 
						$dd = new DropDown();
						$dd->set_table("sport");	
						$dd->set_name_field("sport_name");
						$dd->set_class_name("form-control inline");
						$dd->set_order("ASC");						
						$dd->set_name("promo_sport");						
						$dd->set_selected_value($promo->get_sport());
						$dd->set_required("true");						
						$dd->display();
					 ?>	
					 </td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Item id: </td>
				
					<td>
					<?php 
						$dd = new DropDown();
						$dd->set_table("item");	
						$dd->set_name_field("item_name");
						$dd->set_class_name("form-control inline");
						$dd->set_order("ASC");						
						$dd->set_name("promo_item_id");						
						$dd->set_selected_value($promo->get_item_id());
						$dd->set_required("true");
						$dd->display();
					 ?>											
					</td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Title: </td>
            		<td><input id="promo_title" name="promo_title" type="text"  value="<?php  echo $promo->get_title();  ?>" style="width:90%"  class="{validate:{required:true}}" /> <span class='red'>*</span> </td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Description: </td>
					<td><textarea id="promo_description" name="promo_description" style="width:90%" rows="8" ><?php  echo $promo->get_description();  ?></textarea></td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">View type: </td>

					<td>
					<?php 					
						$dd = new DropDown();
						$dd->set_static(true);	
						$dd->set_name("promo_view_type");
						$dd->set_class_name("form-control inline");
						$dd->set_option_list("All,Brand,Club Exclusive");						
						$dd->set_selected_value($promo->get_view_type());
						$dd->set_required("true");						
						$dd->display();
					 ?>						
					</td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Club: </td>
				
					<td>
					<?php 
						$dd = new DropDown();
						$dd->set_table("club");	
						$dd->set_name_field("club_name");
						$dd->set_class_name("form-control inline");
						$dd->set_order("ASC");						
						$dd->set_name("promo_club_id");						
						$dd->set_selected_value($promo->get_club_id());
						$dd->display();
					 ?>
					 <p class="small">* Only fill this field in if this promo is Club Exclusive</p>
					</td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Price: </td>
					<td>$<input id="promo_price" name="promo_price" type="number" step=".01" value="<?php  echo $promo->get_price();  ?>"  style="width:90%" /></td>
				</tr>
			<!--	<tr>
           			<td style="width:1px; white-space:nowrap;">Image: </td>
            		<td><input id="promo_image" name="promo_image" type="hidden"  value="<?php echo $promo->get_image() ?>" style="width:90%" />
					<p class="small">* PROMOTIONAL graphic, if desired, not the item image.</p>
					</td>
				</tr>-->
				<tr>
           			<td style="width:1px; white-space:nowrap;">Expiry: </td>
            		<td><input id="promo_expiry" name="promo_expiry" type="text"  value="<?php  echo $promo->get_expiry();  ?>" style="width:90%" /> </td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Active: </td>
            		<td>
					<?php 					
						$dd = new DropDown();
						$dd->set_static(true);	
						$dd->set_name("is_active");
						$dd->set_class_name("form-control inline");
						$dd->set_option_list("Y,N");						
						$dd->set_selected_value($promo->get_active());
						$dd->set_required("true");						
						$dd->display();
					 ?>	
					</td>
				</tr>
  		
		</table>
          <br /> 
<<<<<<< HEAD
		  <input name="promo_image" type="hidden"  value="<?php echo $promo->get_image() ?>" />
=======
>>>>>>> f757d9f435864e736cc3bdfe6a140d905e3687d4
          <input type="submit" class="btn btn-success" value="<?php if ($_GET["id"] ==0){ ?> Add <?php  } else { ?> Save <?php  } ?>" />&nbsp;&nbsp;
          <input type="button" class="btn btn-default" value="Back" onClick="window.location ='<?php echo $_SERVER["HTTP_REFERER"];?>'" />
        </form>
		<br>
		
        <?php  if($promo->get_id() > 0){  ?>
          <p><em>Last updated: <?php  echo $promo->get_last_updated();  ?> by <?php  echo $promo->get_last_updated_user();  ?></em></p>
        <?php  }  ?>			
	
      </div>
	<div class="col-md-6">
	<?php if ($promo->get_id() > 0): ?>
			<table class="admin_table">
			<thead>
			<tr><th colspan="5">Image:<i class="fa fa-plus-circle fa-lg add-icon add-image"></i></th></tr>
			</thead>
			
			<tbody id="images_table">
		 <?php 
			echo"<tr><td><img src='images/promo/" .  $promo->get_image() . "'></td></tr>";
		 ?>		
			</tbody>
		</table>
		<br>
	<? endif;?>
	</div>
    </div> 

</div><!-- /container -->
</div>

<?php  include(INCLUDES . "/footer.php");  ?>
<?php  include(INCLUDES_LIST);  ?>	
<script>

$(document).ready(function() {
	$("#price").mask("999999999999.99"); 
});	

</script>
<script type="text/javascript">
	$( "#promo_expiry" ).datepicker({
		dateFormat: "yy-mm-dd",
		changeMonth: true,
		changeYear: true
	});

// Include any masks here:
		 //   $("#student_tel").mask("(999) 999-9999");
		
  </script>	

<script>

$(function() {
	$( "#image_add_dialog" ).dialog({
	// Select item
		width: 550,	
		modal: true,		
		autoOpen: false,
		show: {
			 effect: "fade",
			duration: 300
		},
		hide: {
			effect: "puff",
			percent:110,
			duration: 200
			},
		buttons: {	
			'Add': {
				click: function() {
					$('#newImageForm').submit();	
			   	},
			text: "Add",
			class: 'btn btn-primary'			
            },				
			"Cancel": {
				click: function() {
						$( this ).dialog( "close" );
					},
				text: "Cancel",
				class: 'btn btn-primary'
			}
		}
	});

	// Add component click:
	$(".add-image").on("click", function (e) {
		e.preventDefault();
		$('#image_add_dialog').dialog('open');
	});
});
</script>

<div id="image_add_dialog" title="Image upload" style="display:none">
	<form id="newImageForm" enctype="multipart/form-data"  method="post">
		<input type="hidden" name="id" value="<?php echo $promo->get_id() ?>"/>
		<input type="hidden" name="add_image" value="1"/>
         <table class="admin_table">
				<tr>
            		<td style="background: lightsteelblue;">
					<label for="upload">Select a file...</label><input type="file" name="upload" size="30">
					<br>					
					<!--<input type="submit" name="uploadSubmit" value="Upload">-->
					<p class="small_text">
					* Max upload size 600kb<br>
					* This will overwrite the current image, if set
					</p></td>
				</tr>								 		
		</table>
          <br />
        </form>
</div>	
  	
  </body>  
</html>