<?php 
require("includes/init.php"); 
$page_id = basename(__FILE__);
require(INCLUDES . "/acl_module.php");
include(CLASSES . "/class_itemImage.php"); 
 
	if(!isset($_GET["id"])) {
		$session->setAlertMessage("Can not edit - the ID is invalid. Please try again.");
		$session->setAlertColor("yellow");
		header("location:" . $base_href . "/index.php");
		exit;
	}

	// load the itemImage		
	$itemImage_id = $_GET["id"];
	$itemImage = new ItemImage();
	
	if ($_GET["id"] ==0){
		// Change this to pass a parent value if creating a new record:
		//	$itemImage->set_customer_id($_GET['customer_id']);
	} else {
		$itemImage->get_by_id($itemImage_id);
	}
$activeMenuItem = "ItemImage";				
 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
	<?php  include(HEAD);  ?>
    <meta name="description" content="">

    <title><?php   echo $appConfig["app_title"];  ?> | Item Image Edit</title>
  </head>

  <body>

<?php  require(INCLUDES . "navbar.php");  ?>
<div class="main">

    <div class="container">
      <div class="row">
        <div class="col-md-12">
        <?php  include(INCLUDES . "system_messaging.php");  ?>

        <h1><?php if ($_GET["id"] ==0){ ?> Add Item Image<?php  } else { ?> Edit Item Image<?php  } ?></h1>
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
	<div class="col-md-8">
	<form id="form_itemImage" action="<?php  echo ACTIONS_URL; ?>action_itemImage_edit.php" method="post">
	<input type="hidden" name="itemImage_id" value="<?php  echo $itemImage->get_id();  ?>" />
	<input type="hidden" name="itemImage_item_id" value="<?php  echo $itemImage->get_item_id();  ?>" />	
	<input type="hidden" name="action" value="edit" />	
	<input type="hidden" name="page_id" value="<?php  echo $page_id  ?>" />	
	
         <table class="admin_table">
				<tr>
           			<td style="width:1px; white-space:nowrap;">Url: </td>
            		<td><input id="itemImage_url" name="itemImage_url" type="text"  value="<?php  echo $itemImage->get_url();  ?>" style="width:90%" /> </td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Image Description: </td>
            		<td><input id="itemImage_description" name="itemImage_description" type="text"  value="<?php  echo $itemImage->get_description();  ?>" style="width:90%" /> </td>
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
						$dd->set_selected_value($itemImage->get_active());
						$dd->display();
					 ?>
					<!--<input id="is_active" name="is_active" type="text"  value="<?php  echo $itemImage->get_active();  ?>" style="width:90%" /> -->
					</td>
				</tr>
  		
		</table>
          <br />
<<<<<<< HEAD
          <input type="submit" class="btn btn-success" value="<?php if ($_GET["id"] ==0){ ?> Add <?php  } else { ?> Save <?php  } ?>" />&nbsp;
          <input type="submit" class="btn btn-warning"  value="Delete" name="delete"/>&nbsp;	  		  
          <input type="button" class="btn btn-default" value="Cancel" onClick="window.location ='<?php echo $_SERVER["HTTP_REFERER"];?>'" />
=======
          <input type="submit" class="btn-success" value="<?php if ($_GET["id"] ==0){ ?> Add <?php  } else { ?> Save <?php  } ?>" />&nbsp;
          <input type="submit" class="btn-warning"  value="Delete" name="delete"/>&nbsp;	  		  
          <input type="button" class="btn-default" value="Back" onClick="window.location ='<?php echo $_SERVER["HTTP_REFERER"];?>'" />
>>>>>>> f757d9f435864e736cc3bdfe6a140d905e3687d4
        </form>
		<br>
		
        <?php  if($itemImage->get_id() > 0){  ?>
          <p><em>Last updated: <?php  echo $itemImage->get_last_updated();  ?> by <?php  echo $itemImage->get_last_updated_user();  ?></em></p>
        <?php  }  ?>			
	
      </div>
    </div> 

</div><!-- /container -->
</div>

<?php  include(INCLUDES . "/footer.php");  ?>
<?php  include(INCLUDES_LIST);  ?>	
<script>

</script>
<script type="text/javascript">
		$(document).ready(function() {
			var container = $("div.errorContainer");
			// validate the form when it is submitted
			var validator = $("#form_customers").validate({
				errorContainer: container,
				errorLabelContainer: $("ol", container),
				wrapper: "li",
				meta: "validate"
			});
	 	});

		$.validator.setDefaults({
			submitHandler: function() { form.submit();  }
		});

// Include any masks here:
		 //   $("#student_tel").mask("(999) 999-9999");
		
  </script>		
  </body>
</html>