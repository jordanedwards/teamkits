<?php 
require("../includes/init.php"); 
$page_id = basename(__FILE__);
require(INCLUDES . "/acl_module.php");
include(CLASSES . "/class_clubColours.php"); 
 
	if(!isset($_GET["id"])) {
		$session->setAlertMessage("Can not edit - the ID is invalid. Please try again.");
		$session->setAlertColor("yellow");
		header("location:" . BASE_URL . "/index.php");
		exit;
	}

	// load the clubColours		
	$clubColours_id = $_GET["id"];
	$clubColours = new ClubColours();
	
	if ($_GET["id"] ==0){
		// Change this to pass a parent value if creating a new record:
		//	$clubColours->set_customer_id($_GET['customer_id']);
	} else {
		$clubColours->get_by_id($clubColours_id);
	}
$activeMenuItem = "ClubColours";				
 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
	<?php  include(HEAD);  ?>
    <meta name="description" content="">

    <title><?php   echo $appConfig["app_title"];  ?> | ClubColours Edit</title>
  </head>

  <body>

<?php  require(INCLUDES . "navbar.php");  ?>
<div class="main">

    <div class="container">
      <div class="row">
        <div class="col-md-12">
        <?php  include(INCLUDES . "system_messaging.php");  ?>

        <h1><?php if ($_GET["id"] ==0){ ?> Add ClubColours<?php  } else { ?> Edit ClubColours<?php  } ?></h1>
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
	<form id="form_clubColours" action="<?php  echo ACTIONS_URL; ?>action_clubColours_edit.php" method="post">
	<input type="hidden" name="clubColours_id" value="<?php  echo $clubColours->get_id();  ?>" />
	<input type="hidden" name="action" value="edit" />	
	<input type="hidden" name="page_id" value="<?php  echo $page_id  ?>" />	
	
         <table class="admin_table">
				<tr>
           			<td style="width:1px; white-space:nowrap;">Code: </td>
            		<td><input id="clubColours_code" name="clubColours_code" type="text"  value="<?php  echo $clubColours->get_code();  ?>" style="width:90%" /> </td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Hex code: </td>
            		<td><input id="clubColours_hex_code" name="clubColours_hex_code" type="text"  value="<?php  echo $clubColours->get_hex_code();  ?>" style="width:90%" /> </td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Title: </td>
            		<td><input id="clubColours_title" name="clubColours_title" type="text"  value="<?php  echo $clubColours->get_title();  ?>" style="width:90%"  class="{validate:{required:true}}" /> <span class='red'>*</span> </td>
				</tr>
  		
		</table>
          <br />
          <input type="submit" value="<?php if ($_GET["id"] ==0){ ?> Add <?php  } else { ?> Save <?php  } ?>" />&nbsp;&nbsp;
          <input type="button" value="Cancel" onClick="window.location ='<?php echo $_SERVER["HTTP_REFERER"];?>'" />
        </form>
		<br>
		
        <?php  if($clubColours->get_id() > 0){  ?>
          <p><em>Last updated: <?php  echo $clubColours->get_last_updated();  ?> by <?php  echo $clubColours->get_last_updated_user();  ?></em></p>
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