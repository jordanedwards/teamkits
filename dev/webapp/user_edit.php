<?php 
require("includes/init.php"); 
$page_id = basename(__FILE__);
require(INCLUDES . "/acl_module.php");
include_once(CLASSES . "/class_user.php"); 
 
	if(!isset($_GET["id"])) {
		$session->setAlertMessage("Can not edit - the ID is invalid. Please try again.");
		$session->setAlertColor("yellow");
		header("location:" . $base_href . "/index.php");
		exit;
	}

	// load the user		
	$user_id = $_GET["id"];
	$user = new User();
	
	if ($_GET["id"] ==0){
		// Change this to pass a parent value if creating a new record:
		//	$user->set_customer_id($_GET['customer_id']);
	} else {
		$user->get_by_id($user_id);
	}
$activeMenuItem = "Manage";				
 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
	<?php  include(HEAD);  ?>
    <meta name="description" content="">

    <title><?php   echo $appConfig["app_title"];  ?> | User Edit</title>
  </head>

  <body>

<?php  require(INCLUDES . "navbar.php");  ?>
<div class="main">

    <div class="container">
      <div class="row">
        <div class="col-md-12">
        <?php  include(INCLUDES . "system_messaging.php");  ?>

        <h1><?php if ($_GET["id"] ==0){ ?> Add User<?php  } else { ?> Edit User<?php  } ?></h1>
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
	<form id="form_user" action="<?php  echo ACTIONS_URL; ?>action_user_edit.php" method="post">
	<input type="hidden" name="user_id" value="<?php  echo $user->get_id();  ?>" />
	<input type="hidden" name="action" value="edit" />	
	<input type="hidden" name="page_id" value="<?php  echo $page_id  ?>" />	
	
         <table class="admin_table">
				<tr>
           			<td style="width:1px; white-space:nowrap;">First name: </td>
            		<td><input id="user_first_name" name="user_first_name" type="text"  value="<?php  echo $user->get_first_name();  ?>" style="width:90%" /> </td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Last name: </td>
            		<td><input id="user_last_name" name="user_last_name" type="text"  value="<?php  echo $user->get_last_name();  ?>" style="width:90%" /> </td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Email: </td>
            		<td><input id="user_email" name="user_email" type="email"  value="<?php  echo $user->get_email();  ?>" style="width:90%" required/> </td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Tel: </td>
					<td><input id="user_tel" name="user_tel" type="tel" value="<?php  echo $user->get_tel();  ?>"  style="width:90%" /></td>

				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Password: </td>
            		<td><input id="user_password" name="user_password" type="password"   placeholder="Enter new password to update" style="width:90%" /> </td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Role: </td>

					<td>
					<?php 					
					$dd = New DropDown();
					$dd->set_table("userrole");
					$dd->set_name_field("userrole_title");
					$dd->set_name_field_2("userrole_group");					
					$dd->set_name("user_role");
					$dd->set_selected_value($user->get_role());
					$dd->set_class_name("form-control inline");
					$dd->set_active_only(true);
					$dd->set_required(true);
					$dd->set_order_by("userrole_id");						
					$dd->set_order("ASC");	
					$dd->display();
				?>
				</td>
				</tr>
  		
		</table>
          <br />
          <input type="submit" class="btn btn-success" value="<?php if ($_GET["id"] ==0){ ?> Add <?php  } else { ?> Save <?php  } ?>" />&nbsp;&nbsp;
          <input type="button" class="btn btn-default" value="Back" onClick="window.location ='<?php echo $_SERVER["HTTP_REFERER"];?>'" />
        </form>
		<br>
		
        <?php  if($user->get_id() > 0){  ?>
          <p><em>Last updated: <?php  echo $user->get_last_updated();  ?> by <?php  echo $user->get_last_updated_user();  ?></em></p>
        <?php  }  ?>			
	
      </div>
    </div> 

</div><!-- /container -->
</div>

<?php  include(INCLUDES . "/footer.php");  ?>
<?php  include(INCLUDES_LIST);  ?>	
<script>

$(document).ready(function() {
	$("#<?php echo $key?>").mask("(999) 999-9999"); 
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