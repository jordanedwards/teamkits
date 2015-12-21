<?php 
require("includes/init.php"); 
$page_id = basename(__FILE__);
require(INCLUDES . "/acl_module.php");
include(CLASSES . "/class_promo.php"); 
 
	if(!isset($_GET["id"])) {
		$session->setAlertMessage("Can not edit - the ID is invalid. Please try again.");
		$session->setAlertColor("yellow");
		header("location:" . $base_href . "/index.php");
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
$activeMenuItem = "Promos";				
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
	<div class="col-md-8">
	<form id="form_promo" action="<?php  echo ACTIONS_URL; ?>action_promo_edit.php" method="post">
	<input type="hidden" name="promo_id" value="<?php  echo $promo->get_id();  ?>" />
	<input type="hidden" name="action" value="edit" />	
	<input type="hidden" name="page_id" value="<?php  echo $page_id  ?>" />	
	
         <table class="admin_table">
				<tr>
           			<td style="width:1px; white-space:nowrap;">Sport: </td>
				
					<td>
					<?php 
						$dd = new DropDown();
						$dd->set_table("sport");	
						$dd->set_name_field("sport_name");
						$dd->set_class_name("form-control");
						$dd->set_order("ASC");						
						$dd->set_name("promo_sport");						
						$dd->set_selected_value($promo->get_sport());
						$dd->display();
					 ?>											
					
					</td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Title: </td>
            		<td><input id="promo_title" name="promo_title" type="text"  value="<?php  echo $promo->get_title();  ?>" style="width:90%" /> </td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Description: </td>
            		<td><input id="promo_description" name="promo_description" type="text"  value="<?php  echo $promo->get_description();  ?>" style="width:90%" /> </td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">View list: </td>

					<td>
					<?php 					
						$dd = new DropDown();
						$dd->set_static(true);	
						$dd->set_name("promo_view_list");
						$dd->set_class_name("form-control");
						$dd->set_option_list("Y,N");						
						$dd->set_selected_value($promo->get_view_list());
						$dd->display();
					 ?>						
					</td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Wholesale: </td>
            		<td><input id="promo_wholesale" name="promo_wholesale" type="text"  value="<?php  echo $promo->get_wholesale();  ?>" style="width:90%" /> </td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Price: </td>
            		<td><input id="promo_price" name="promo_price" type="text"  value="<?php  echo $promo->get_price();  ?>" style="width:90%" /> </td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Image: </td>
            		<td><input id="promo_image" name="promo_image" type="text"  value="<?php  echo $promo->get_image();  ?>" style="width:90%" /> </td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Tax: </td>
				
					<td>
					<?php 
						$dd = new DropDown();
						$dd->set_table("tax");	
						$dd->set_name_field("tax_name");
						$dd->set_class_name("form-control");
						$dd->set_order("ASC");						
						$dd->set_name("promo_tax");						
						$dd->set_selected_value($promo->get_tax());
						$dd->display();
					 ?>											
					
					</td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Active: </td>

					<td>
					<?php 					
						$dd = new DropDown();
						$dd->set_static(true);	
						$dd->set_name("is_active");
						$dd->set_class_name("form-control");
						$dd->set_option_list("Y,N");						
						$dd->set_selected_value($promo->get_active());
						$dd->display();
					 ?>						
					</td>
				</tr>
  		
		</table>
          <br />
          <input type="submit" value="Add/Update Promo" />&nbsp;&nbsp;
          <input type="button" value="Back" onClick="window.location ='<?php echo $_SERVER["HTTP_REFERER"];?>'" />
        </form>
		<br>
		
        <?php  if($promo->get_id() > 0){  ?>
          <p><em>Last updated: <?php  echo $promo->get_last_updated();  ?> by <?php  echo $promo->get_last_updated_user();  ?></em></p>
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