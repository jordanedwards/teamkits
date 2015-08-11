<?php 
require("includes/init.php"); 
$page_id = basename(__FILE__);
require(INCLUDES . "/acl_module.php");
include(CLASSES . "/class_clubUploads.php"); 
 
	if(!isset($_GET["id"])) {
		$session->setAlertMessage("Can not edit - the ID is invalid. Please try again.");
		$session->setAlertColor("yellow");
		header("location:" . BASE_URL . "/index.php");
		exit;
	}

	// load the clubUploads		
	$clubUploads_id = $_GET["id"];
	$clubUploads = new ClubUploads();
	
	if ($_GET["id"] ==0){
		// Change this to pass a parent value if creating a new record:
		//	$clubUploads->set_customer_id($_GET['customer_id']);
	} else {
		$clubUploads->get_by_id($clubUploads_id);
	}
$activeMenuItem = "ClubUploads";				
 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
	<?php  include(HEAD);  ?>
    <meta name="description" content="">

    <title><?php   echo $appConfig["app_title"];  ?> | ClubUploads Edit</title>
  </head>

  <body>

<?php  require(INCLUDES . "navbar.php");  ?>
<div class="main">

    <div class="container">
      <div class="row">
        <div class="col-md-12">
        <?php  include(INCLUDES . "system_messaging.php");  ?>

        <h1><?php if ($_GET["id"] ==0){ ?> Add ClubUploads<?php  } else { ?> Edit ClubUploads<?php  } ?></h1>
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
	<form id="form_clubUploads" action="<?php  echo ACTIONS_URL; ?>action_clubUploads_edit.php" method="post">
	<input type="hidden" name="clubUploads_id" value="<?php  echo $clubUploads->get_id();  ?>" />
	<input type="hidden" name="action" value="edit" />	
	<input type="hidden" name="page_id" value="<?php  echo $page_id  ?>" />	
	
         <table class="admin_table">
				<tr>
           			<td style="width:1px; white-space:nowrap;">Title: </td>
            		<td><input id="clubUploads_title" name="clubUploads_title" type="text"  value="<?php  echo $clubUploads->get_title();  ?>" style="width:90%"  class="{validate:{required:true}}" /> <span class='red'>*</span> </td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Note: </td>
					<td><textarea id="clubUploads_note" name="clubUploads_note" style="width:90%" rows="8" ><?php  echo $clubUploads->get_note();  ?></textarea></td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Active: </td>
            		<td><input id="is_active" name="is_active" type="text"  value="<?php  echo $clubUploads->get_active();  ?>" style="width:90%" /> </td>
				</tr>
  		
		</table>
          <br />
          <input type="submit" value="<?php if ($_GET["id"] ==0){ ?> Add <?php  } else { ?> Save <?php  } ?>" />&nbsp;&nbsp;
          <input type="button" value="Back" onClick="window.location ='<?php echo $_SERVER["HTTP_REFERER"];?>'" />
        </form>
		<br>
		
        <?php  if($clubUploads->get_id() > 0){  ?>
          <p><em>Last updated: <?php  echo $clubUploads->get_last_updated();  ?> by <?php  echo $clubUploads->get_last_updated_user();  ?></em></p>
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