<?php 
require("includes/init.php"); 
$page_id = basename(__FILE__);
require(INCLUDES . "/acl_module.php");
include(CLASSES . "/class_club.php"); 
 
	if(!isset($_GET["id"])) {
		$session->setAlertMessage("Can not edit - the ID is invalid. Please try again.");
		$session->setAlertColor("yellow");
		header("location:" . BASE_URL . "/index.php");
		exit;
	}

	// load the club		
	$club_id = $_GET["id"];
	$club = new Club();
	
	if ($_GET["id"] ==0){
		// Change this to pass a parent value if creating a new record:
		//	$club->set_customer_id($_GET['customer_id']);
	} else {
		$club->get_by_id($club_id);
	}
$activeMenuItem = "Club";				
 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
	<?php  include(HEAD);  ?>
    <meta name="description" content="">

    <title><?php   echo $appConfig["app_title"];  ?> | Club Edit</title>
  </head>

  <body>

<?php  require(INCLUDES . "navbar.php");  ?>
<div class="main">

    <div class="container">
      <div class="row">
        <div class="col-md-12">
        <?php  include(INCLUDES . "system_messaging.php");  ?>

        <h1><?php if ($_GET["id"] ==0){ ?> Add Club<?php  } else { ?> Edit Club<?php  } ?></h1>
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
	<form id="form_club" action="<?php  echo ACTIONS_URL; ?>action_club_edit.php" method="post">
	<input type="hidden" name="club_id" value="<?php  echo $club->get_id();  ?>" />
	<input type="hidden" name="action" value="edit" />	
	<input type="hidden" name="page_id" value="<?php  echo $page_id  ?>" />	
	
         <table class="admin_table">
				<tr>
           			<td style="width:1px; white-space:nowrap;">Name: </td>
            		<td><input id="club_name" name="club_name" class="form-control inline" type="text"  value="<?php  echo $club->get_name();  ?>"  /></td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Sport: </td>
				
					<td>
					<?php 
						$dd = new DropDown();
						$dd->set_table("sport");	
						$dd->set_name_field("sport_name");
						$dd->set_class_name("form-control inline");
						$dd->set_order("ASC");						
						$dd->set_name("club_sport");						
						$dd->set_selected_value($club->get_sport());
						$dd->display();
					 ?>											
					</td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Brand: </td>
            		<td><input id="club_brand" name="club_brand" class="form-control inline" type="number" step="any" value="<?php  echo $club->get_brand();  ?>"  /></td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Tel: </td>
					<td><input id="club_tel" name="club_tel" class="form-control inline" type="tel" value="<?php  echo $club->get_tel();  ?>"  /></td>

				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Address: </td>
            		<td><input id="club_address" name="club_address" class="form-control inline" type="text"  value="<?php  echo $club->get_address();  ?>"  /></td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">City: </td>
            		<td><input id="club_city" name="club_city" class="form-control inline" type="text"  value="<?php  echo $club->get_city();  ?>"  /></td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Province: </td>
            		<td><input id="club_province" name="club_province" class="form-control inline" type="number" step="any" value="<?php  echo $club->get_province();  ?>"  /></td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Country: </td>
            		<td><input id="club_country" name="club_country" class="form-control inline" type="number" step="any" value="<?php  echo $club->get_country();  ?>"  /></td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Postal code: </td>
            		<td><input id="club_postal_code" name="club_postal_code" class="form-control inline" type="text"  value="<?php  echo $club->get_postal_code();  ?>"  /></td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Login: </td>
            		<td><input id="club_login" name="club_login" class="form-control inline" type="text"  value="<?php  echo $club->get_login();  ?>"  /></td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Password: </td>
            		<td><input id="club_password" name="club_password" class="form-control inline" type="password"  value="<?php  echo $club->get_password();  ?>"  /></td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Code: </td>
            		<td><input id="club_code" name="club_code" class="form-control inline" type="text"  value="<?php  echo $club->get_code();  ?>"  /></td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Account type: </td>
            		<td><input id="club_account_type" name="club_account_type" class="form-control inline" type="number" step="any" value="<?php  echo $club->get_account_type();  ?>"  /></td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Tax id: </td>
				
					<td>
					<?php 
						$dd = new DropDown();
						$dd->set_table("tax");	
						$dd->set_name_field("tax_title");
						$dd->set_class_name("form-control inline");
						$dd->set_order("ASC");						
						$dd->set_name("club_tax_id");						
						$dd->set_selected_value($club->get_tax_id());
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
						$dd->set_class_name("form-control inline");
						$dd->set_option_list("Y,N");						
						$dd->set_selected_value($club->get_active());
	
						$dd->display();
					 ?>											
					</td>
				</tr>
  		
		</table>
          <br />
          <input type="submit" value="<?php if ($_GET["id"] ==0){ ?> Add <?php  } else { ?> Save <?php  } ?>" />&nbsp;&nbsp;
          <input type="button" value="Back" onClick="window.location ='<?php echo $_SERVER["HTTP_REFERER"];?>'" />
        </form>
		<br>
		
        <?php  if($club->get_id() > 0){  ?>
          <p><em>Last updated: <?php  echo $club->get_last_updated();  ?> by <?php  echo $club->get_last_updated_user();  ?></em></p>
        <?php  }  ?>			
	
      </div>
    </div> 

</div><!-- /container -->
</div>

<?php  include(INCLUDES . "/footer.php");  ?>
<?php  include(INCLUDES_LIST);  ?>	
<script>

$(document).ready(function() {
	$("#club_tel").mask("(999) 999-9999"); 
});	

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