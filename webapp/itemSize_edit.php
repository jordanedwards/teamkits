<?php 
require("includes/init.php"); 
$page_id = basename(__FILE__);
require(INCLUDES . "/acl_module.php");
include(CLASSES . "/class_itemSize.php"); 
 
	if(!isset($_GET["id"])) {
		$session->setAlertMessage("Can not edit - the ID is invalid. Please try again.");
		$session->setAlertColor("yellow");
		header("location:" . BASE_URL . "/index.php");
		exit;
	}

	// load the itemSize		
	$itemSize_id = $_GET["id"];
	$itemSize = new ItemSize();
	
	if ($_GET["id"] ==0){
		// Change this to pass a parent value if creating a new record:
		//	$itemSize->set_customer_id($_GET['customer_id']);
	} else {
		$itemSize->get_by_id($itemSize_id);
	}
$activeMenuItem = "ItemSize";				
 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
	<?php  include(HEAD);  ?>
    <meta name="description" content="">

    <title><?php   echo $appConfig["app_title"];  ?> | ItemSize Edit</title>
  </head>

  <body>

<?php  require(INCLUDES . "navbar.php");  ?>
<div class="main">

    <div class="container">
      <div class="row">
        <div class="col-md-12">
        <?php  include(INCLUDES . "system_messaging.php");  ?>

        <h1><?php if ($_GET["id"] ==0){ ?> Add Item Size<?php  } else { ?> Edit Item Size<?php  } ?></h1>
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
	<form id="form_itemSize" action="<?php  echo ACTIONS_URL; ?>action_itemSize_edit.php" method="post">
	<input type="hidden" name="itemSize_id" value="<?php  echo $itemSize->get_id();  ?>" />
	<input type="hidden" name="action" value="edit" />	
	<input type="hidden" name="page_id" value="<?php  echo $page_id  ?>" />	
	
         <table class="admin_table">
				<tr>
           			<td style="width:1px; white-space:nowrap;">Item: </td>
				
					<td>
					<?php 
						$dd = new DropDown();
						$dd->set_table("item");	
						$dd->set_name_field("item_name");
						$dd->set_class_name("form-control inline");
						$dd->set_order("ASC");						
						$dd->set_name("itemSize_item_id");						
						$dd->set_selected_value($itemSize->get_item_id());
						$dd->set_required(true);						
						$dd->display();
					 ?>											
					
					</td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Name: </td>
            		<td>
					<?php 
						$dd = new DropDown();
						$dd->set_table("sizes");	
						$dd->set_name_field("sizes_name");
						$dd->set_class_name("form-control inline");
						$dd->set_order("ASC");						
						$dd->set_name("itemSize_name");						
						$dd->set_selected_value($itemSize->get_name());
						$dd->set_index_name("sizes_name");
						$dd->set_required(true);						
						$dd->display();
					 ?>						
					</td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Stock: </td>
            		<td><input id="itemSize_stock" name="itemSize_stock" type="number" step="any" value="<?php  echo $itemSize->get_stock();  ?>" style="width:90%" /> </td>
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
						$dd->set_selected_value($itemSize->get_active());
						$dd->set_required(true);
						$dd->display();
					 ?>	
					 </td>
				</tr>
  		
		</table>
          <br />
          <input type="submit" class="btn btn-success" value="<?php if ($_GET["id"] ==0){ ?> Add <?php  } else { ?> Save <?php  } ?>" />&nbsp;&nbsp;
          <input type="button"  class="btn btn-default" value="Back" onClick="window.location ='<?php echo $_SERVER["HTTP_REFERER"];?>'" />
        </form>
		<br>
		
        <?php  if($itemSize->get_id() > 0){  ?>
          <p><em>Last updated: <?php  echo $itemSize->get_last_updated();  ?> by <?php  echo $itemSize->get_last_updated_user();  ?></em></p>
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