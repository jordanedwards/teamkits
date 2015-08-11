<?php 
require("includes/init.php"); 
$page_id = basename(__FILE__);
require(INCLUDES . "/acl_module.php");
include(CLASSES . "/class_clubNotes.php"); 
 
	if(!isset($_GET["id"])) {
		$session->setAlertMessage("Can not edit - the ID is invalid. Please try again.");
		$session->setAlertColor("yellow");
		header("location:" . $base_href . "/index.php");
		exit;
	}

	// load the clubNotes		
	$clubNotes_id = $_GET["id"];
	$clubNotes = new ClubNotes();
	
	if ($_GET["id"] ==0){
		// Change this to pass a parent value if creating a new record:
		//	$clubNotes->set_customer_id($_GET['customer_id']);
	} else {
		$clubNotes->get_by_id($clubNotes_id);
	}
$activeMenuItem = "ClubNotes";				
 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
	<?php  include(HEAD);  ?>
    <meta name="description" content="">

    <title><?php   echo $appConfig["app_title"];  ?> | Club Note Edit</title>
  </head>

  <body>

<?php  require(INCLUDES . "navbar.php");  ?>
<div class="main">

    <div class="container">
      <div class="row">
        <div class="col-md-12">
        <?php  include(INCLUDES . "system_messaging.php");  ?>

        <h1><?php if ($_GET["id"] ==0){ ?> Add Club Note<?php  } else { ?> Edit Club Note<?php  } ?></h1>
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
	<form id="form_clubNotes" action="<?php  echo ACTIONS_URL; ?>action_clubNotes_edit.php" method="post">
	<input type="hidden" name="id" value="<?php  echo $clubNotes->get_id();  ?>" />
	<input type="hidden" name="action" value="edit" />	
	<input type="hidden" name="page_id" value="<?php  echo $page_id  ?>" />	
	
         <table class="admin_table">
				<tr>
           			<td style="width:1px; white-space:nowrap;">Content: </td>
            		<td><textarea id="content" name="content" type="text" class="form-control" rows="8"><?php  echo $clubNotes->get_content();  ?></textarea></td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Follow-up Date: </td>
            		<td><input id="followup_date" name="followup_date" type="text" class="form-control" value="<?php  echo $clubNotes->get_followup_date();  ?>"></td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Follow-up Complete: </td>
            		<td>
					<?php 
					$dd = new DropDown();
					$dd->set_static(true);	
					$dd->set_name("followup_complete");
					$dd->set_class_name("form-control");
					$dd->set_selected_value($clubNotes->get_followup_complete());
					$dd->set_option_list("Y,N");
					$dd->display();
					?>
					</td>
				</tr>									
  		
		</table>
          <br />
          <input type="submit" class="btn btn-success" value="<?php if ($_GET["id"] ==0){ ?> Add <?php  } else { ?> Save <?php  } ?>" />&nbsp;&nbsp;
	      <?php if ($_GET["id"] !=0){ ?>  <input type="submit" name="delete" class="btn btn-warning" value="Delete" />&nbsp;&nbsp;<?php } ?>		  
		  
          <input type="button" class="btn btn-default" value="Back" onClick="window.location ='<?php echo $_SERVER["HTTP_REFERER"];?>'" />
        </form>
		<br>
		
        <?php  if($clubNotes->get_id() > 0){  ?>
          <p><em>Last updated: <?php  echo $clubNotes->get_last_updated();  ?> by <?php  echo $clubNotes->get_last_updated_user();  ?></em></p>
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

	$( "#followup_date" ).datepicker({
		dateFormat: "yy-mm-dd",
		changeMonth: true,
		changeYear: true
});
		
  </script>		
  </body>
</html>