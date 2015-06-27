<?php 
require("includes/init.php"); 
$page_id = basename(__FILE__);
require(INCLUDES . "/acl_module.php");
include(CLASSES . "/class_club.php"); 
 
	if(!isset($_GET["id"])) {
		$session->setAlertMessage("Can not edit - the ID is invalid. Please try again.");
		$session->setAlertColor("yellow");
		header("location:" . $base_href . "/index.php");
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
$activeMenuItem = "Clubs";				
 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
	<?php  include(HEAD);  ?>
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
	<div class="col-md-6">
	<form id="form_club" action="<?php  echo ACTIONS_URL; ?>action_club_edit.php" method="post">
	<input type="hidden" name="club_id" value="<?php  echo $club->get_id();  ?>" />
	<input type="hidden" name="action" value="edit" />	
	<input type="hidden" name="page_id" value="<?php  echo $page_id  ?>" />	
	
         <table class="admin_table">
				<tr>
           			<td style="width:1px; white-space:nowrap;">Name: </td>
            		<td><input id="club_name" name="club_name" type="text"  value="<?php  echo $club->get_name();  ?>" style="width:90%"  class="{validate:{required:true}}" /> <span class='red'>*</span> </td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Sport: </td>
				
					<td>
					<?php 
						$dd = new DropDown();
						$dd->set_table("sport");	
						$dd->set_name_field("sport_name");
						$dd->set_class_name("form-control");
						$dd->set_order("ASC");						
						
						$dd->set_name("club_sport");						
						$dd->set_selected_value($club->get_sport());
						$dd->display();
					 ?>											
					
					</td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Brand: </td>
				
					<td>
					<?php 
						$dd = new DropDown();
						$dd->set_table("brand");	
						$dd->set_name_field("brand_name");
						$dd->set_class_name("form-control");
						$dd->set_order("ASC");						
						
						$dd->set_name("club_brand");						
						$dd->set_selected_value($club->get_brand());
						$dd->display();
					 ?>											
					
					</td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Tel: </td>
					<td><input id="club_tel" name="club_tel" type="tel" value="<?php  echo $club->get_tel();  ?>"  style="width:90%" /></td>
<script type="text/javascript">
$(document).ready(function() {
	$("#club_tel").mask("(999) 999-9999"); 
});	
</script>	
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Address: </td>
            		<td><input id="club_address" name="club_address" type="text"  value="<?php  echo $club->get_address();  ?>" style="width:90%" /> </td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">City: </td>
            		<td><input id="club_city" name="club_city" type="text"  value="<?php  echo $club->get_city();  ?>" style="width:90%" /> </td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Province: </td>
				
					<td>
					<?php 
						$dd = new DropDown();
						$dd->set_table("province");	
						$dd->set_name_field("province_name");
						$dd->set_class_name("form-control");
						$dd->set_order("ASC");						
						
						$dd->set_name("club_province");						
						$dd->set_selected_value($club->get_province());
						$dd->display();
					 ?>											
					
					</td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Country: </td>
				
					<td>
					<?php 
						$dd = new DropDown();
						$dd->set_table("country");	
						$dd->set_name_field("country_name");
						$dd->set_class_name("form-control");
						$dd->set_order("ASC");						
						
						$dd->set_name("club_country");						
						$dd->set_selected_value($club->get_country());
						$dd->display();
					 ?>											
					
					</td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Postal code: </td>
            		<td><input id="club_postal_code" name="club_postal_code" type="text"  value="<?php  echo $club->get_postal_code();  ?>" style="width:90%" /> </td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Tax: </td>
					<td>
					<?php 
						$dd = new DropDown();
						$dd->set_table("tax");	
						$dd->set_name_field("tax_title");
						$dd->set_class_name("form-control");
						$dd->set_order("ASC");
						$dd->set_name("club_tax_id");						
						$dd->set_selected_value($club->get_tax_id());
						$dd->display();
					 ?>											
					
					</td>
				</tr>				
				<tr>
           			<td style="width:1px; white-space:nowrap;">Login: </td>
            		<td><input id="club_login" name="club_login" type="text"  value="<?php  echo $club->get_login();  ?>" style="width:90%" /> </td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Password: </td>
            		<td><input id="club_password" name="club_password" type="password"  value="<?php  echo $club->get_password();  ?>" style="width:90%" /> </td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Code: </td>
            		<td><input id="club_code" name="club_code" type="text"  value="<?php  echo $club->get_code();  ?>" style="width:90%" /> </td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Account type: </td>
					<td>
					<?php 
						$dd = new DropDown();
						$dd->set_table("accounttype");	
						$dd->set_name_field("accounttype_title");
						$dd->set_class_name("form-control");
						$dd->set_order("ASC");
						$dd->set_name("club_account_type");						
						$dd->set_selected_value($club->get_account_type());
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
						$dd->set_selected_value($club->get_active());
						$dd->display();
					 ?>						
					</td>
				</tr>
  		
		</table>
          <br />
          <input type="submit" value="Add/Update Club" />&nbsp;&nbsp;
          <input type="button" value="Cancel" onClick="window.location ='<?php echo $_SERVER["HTTP_REFERER"];?>'" />
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
<?php require(INCLUDES_LIST);?>	 

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