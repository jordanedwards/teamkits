<?php 
require("includes/init.php"); 
$page_id = basename(__FILE__);
require(INCLUDES . "/acl_module.php");
include(CLASSES . "/class_shippingrecord.php"); 
 
	if(!isset($_GET["id"])) {
		$session->setAlertMessage("Can not edit - the ID is invalid. Please try again.");
		$session->setAlertColor("yellow");
		header("location:" . $base_href . "/index.php");
		exit;
	}

	// load the shippingrecord		
	$shippingrecord_id = $_GET["id"];
	$shippingrecord = new Shippingrecord();
	
	if ($_GET["id"] ==0){
		// Change this to pass a parent value if creating a new record:
		//	$shippingrecord->set_customer_id($_GET['customer_id']);
	} else {
		$shippingrecord->get_by_id($shippingrecord_id);
	}
$activeMenuItem = "Shippingrecord";				
 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
	<?php  include(HEAD);  ?>
    <meta name="description" content="">

    <title><?php   echo $appConfig["app_title"];  ?> | Shipping Record Edit</title>
  </head>

  <body>

<?php  require(INCLUDES . "navbar.php");  ?>
<div class="main">

    <div class="container">
      <div class="row">
        <div class="col-md-12">
        <?php  include(INCLUDES . "system_messaging.php");  ?>

        <h1><?php if ($_GET["id"] ==0){ ?> Add Shipping Record<?php  } else { ?> Edit Shipping Record<?php  } ?></h1>
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
	<form id="form_shippingrecord" action="<?php  echo ACTIONS_URL; ?>action_shippingrecord_edit.php" method="post">
	<input type="hidden" name="shippingrecord_id" value="<?php  echo $shippingrecord->get_id();  ?>" />
	<input type="hidden" name="action" value="edit" />	
	<input type="hidden" name="page_id" value="<?php  echo $page_id  ?>" />	
	
         <table class="admin_table">
				<tr>
           			<td style="width:1px; white-space:nowrap;">Carrier: </td>

					<td>
					<?php 	
						include_once(CLASSES . "class_dd_preset.php");				
						$dd = new DD_preset("shippingrecord");
						$dd->set_selected_value($shippingrecord->get_carrier());
						$dd->display();
					 ?>						
					</td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Tracking: </td>
            		<td><input id="shippingrecord_tracking" name="shippingrecord_tracking" type="text"  value="<?php  echo $shippingrecord->get_tracking();  ?>" style="width:90%" /> </td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Date: </td>
            		<td><input id="shippingrecord_date" name="shippingrecord_date" type="text"  value="<?php  echo $shippingrecord->get_date();  ?>" style="width:90%"  class="form-control inline" required/></td>
				</tr>
  		
		</table>
          <br />
          <input type="submit" class="btn btn-success" value="<?php if ($_GET["id"] ==0){ ?> Add <?php  } else { ?> Save <?php  } ?>" />&nbsp;
          <input type="submit" class="btn btn-warning"  value="Delete" name="delete"/>&nbsp;	  
<<<<<<< HEAD
          <input type="button" class="btn btn-default"  value="Cancel" onClick="window.location ='<?php echo $_SERVER["HTTP_REFERER"];?>'" />
=======
          <input type="button" class="btn btn-default"  value="Back" onClick="window.location ='<?php echo $_SERVER["HTTP_REFERER"];?>'" />
>>>>>>> f757d9f435864e736cc3bdfe6a140d905e3687d4
        </form>
		<br>
		
        <?php  if($shippingrecord->get_id() > 0){  ?>
          <p><em>Last updated: <?php  echo $shippingrecord->get_last_updated();  ?> by <?php  echo $shippingrecord->get_last_updated_user();  ?></em></p>
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

	$( "#shippingrecord_date" ).datepicker({
		dateFormat: "yy-mm-dd",
		changeMonth: true,
		changeYear: true
	});
		
  </script>		
  </body>
</html>