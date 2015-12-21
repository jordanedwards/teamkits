<?php 
require("includes/init.php"); 
$page_id = basename(__FILE__);
require(INCLUDES . "/acl_module.php");
include(CLASSES . "/class_clubContact.php"); 
 
	if(!isset($_GET["id"])) {
		$session->setAlertMessage("Can not edit - the ID is invalid. Please try again.");
		$session->setAlertColor("yellow");
		header("location:" . BASE_URL . "/index.php");
		exit;
	}

	// load the clubContact		
	$clubContact_id = $_GET["id"];
	$clubContact = new ClubContact();
	
	if ($_GET["id"] ==0){
		// Change this to pass a parent value if creating a new record:
		//	$clubContact->set_customer_id($_GET['customer_id']);
	} else {
		$clubContact->get_by_id($clubContact_id);
	}
$activeMenuItem = "ClubContact";				
 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
	<?php  include(HEAD);  ?>
    <meta name="description" content="">

    <title><?php   echo $appConfig["app_title"];  ?> | Club Contact Edit</title>
  </head>

  <body>

<?php  require(INCLUDES . "navbar.php");  ?>
<div class="main">

    <div class="container">
      <div class="row">
        <div class="col-md-12">
        <?php  include(INCLUDES . "system_messaging.php");  ?>

        <h1><?php if ($_GET["id"] ==0){ ?> Add Club Contact<?php  } else { ?> Edit Club Contact<?php  } ?></h1>
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
	<form id="form_clubContact" action="<?php  echo ACTIONS_URL; ?>action_clubContact_edit.php" method="post">
	<input type="hidden" name="clubContact_id" value="<?php  echo $clubContact->get_id();  ?>" />
	<input type="hidden" name="action" value="edit" />	
	<input type="hidden" name="page_id" value="<?php  echo $page_id  ?>" />	
	
         <table class="admin_table">
				<tr>
           			<td style="width:1px; white-space:nowrap;">Name: </td>
            		<td><input id="clubContact_name" name="clubContact_name" type="text"  value="<?php  echo $clubContact->get_name();  ?>" style="width:90%"  class="{validate:{required:true}}" /> <span class='red'>*</span> </td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Position: </td>
            		<td><input id="clubContact_position" name="clubContact_position" type="text"  value="<?php  echo $clubContact->get_position();  ?>" style="width:90%" /> </td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Tel: </td>
					<td><input id="clubContact_tel" name="clubContact_tel" type="tel" value="<?php  echo $clubContact->get_tel();  ?>"  style="width:90%" /></td>

				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Email: </td>
            		<td><input id="clubContact_email" name="clubContact_email" type="email"  value="<?php  echo $clubContact->get_email();  ?>" style="width:90%" /> </td>
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
						$dd->set_selected_value($clubContact->get_active());
						$dd->display();
					 ?>						
					<span class='red'> *</span> </td>
				</tr>
  		
		</table>
          <br />
          <input type="submit" class="btn btn-success" value="<?php if ($_GET["id"] ==0){ ?> Add <?php  } else { ?> Save <?php  } ?>" />&nbsp;&nbsp;
        <?php if ($_GET["id"] !=0){ ?>  <input type="submit" class="btn btn-warning" name="delete" value="Delete" />&nbsp;&nbsp;<?php } ?>		  
          <input type="button" value="Cancel" class="btn btn-default" onClick="window.location ='<?php echo $_SERVER["HTTP_REFERER"];?>'" />
        </form>
		<br>
		
        <?php  if($clubContact->get_id() > 0){  ?>
          <p><em>Last updated: <?php  echo $clubContact->get_last_updated();  ?> by <?php  echo $clubContact->get_last_updated_user();  ?></em></p>
        <?php  }  ?>			
	
      </div>
    </div> 

</div><!-- /container -->
</div>

<?php  include(INCLUDES . "/footer.php");  ?>
<?php  include(INCLUDES_LIST);  ?>	
<script>

$(document).ready(function() {
	$("#clubContact_tel").mask("(999) 999-9999"); 
});	

</script>
	
  </body>
</html>