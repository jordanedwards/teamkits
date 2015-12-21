<?php 
require("includes/init.php"); 
$page_id = basename(__FILE__);
$activeMenuItem = "Build";				
require(INCLUDES . "/acl_module.php");
require_once(CLASSES . "/class_build.php"); 
 
if(!isset($_GET["id"])) {
	$session->setAlertMessage("Can not edit - the ID is invalid. Please try again.");
	$session->setAlertColor("yellow");
	header("location:" . BASE_URL . "/index.php");
	exit;
}

// load the build		
$build_id = $_GET["id"];
$build = new Build();

if ($_GET["id"] ==0){
	// Change this to pass a parent value if creating a new record:
	//	$build->set_customer_id($_GET['customer_id']);
} else {
	$build->get_by_id($build_id);
}
 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
	<?php  include(HEAD);  ?>
    <meta name="description" content="">

    <title><?php   echo $appConfig["app_title"];  ?> | Build Edit</title>
  </head>

  <body>

<?php  require(INCLUDES . "navbar.php");  ?>
<div class="main">

    <div class="container">
      <div class="row">
        <div class="col-md-12">
        <?php  include(INCLUDES . "system_messaging.php");  ?>

        <h1><?php if ($_GET["id"] ==0){ ?> Add Build<?php  } else { ?> Edit Build<?php  } ?></h1>
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
	<form id="form_build" action="<?php  echo ACTIONS_URL; ?>action_build_edit.php" method="post">
	<input type="hidden" name="id" value="<?php  echo $build->get_id();  ?>" />
	<input type="hidden" name="action" value="edit" />	
	<input type="hidden" name="page_id" value="<?php  echo $page_id  ?>" />	
	
         <table class="admin_table">
				<tr>
           			<td style="width:1px; white-space:nowrap;">Club id: </td>
				
					<td>
					<?php 
						$dd = new DropDown();
						$dd->set_table("club");	
						$dd->set_name_field("club_name");
						$dd->set_class_name("form-control inline");
						$dd->set_order("ASC");						
						$dd->set_name("club_id");						
						$dd->set_selected_value($build->get_club_id());
						$dd->set_required("true");
						$dd->display();
					 ?>											
					</td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Title: </td>
            		<td><input id="title" name="title" class="form-control inline" type="text"  value="<?php  echo $build->get_title();  ?>"   required /></td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Note: </td>
					<td><textarea id="note" name="note" class="form-control inline" rows="8" ><?php  echo $build->get_note();  ?></textarea></td>
					
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Is active: </td>

					<td>
					<?php 					
						$dd = new DropDown();
						$dd->set_static(true);	
						$dd->set_name("is_active");
						$dd->set_class_name("form-control inline");
						$dd->set_option_list("Y,N");						
						$dd->set_selected_value($build->get_is_active());
	
						$dd->display();
					 ?>											
					</td>
				</tr>
  		
		</table>
          <br />
          <input type="submit" class="btn-primary" value="<?php if ($_GET["id"] ==0){ ?> Add <?php  } else { ?> Save <?php  } ?>" />&nbsp;&nbsp;
          <input type="button" class="btn-default" value="Cancel" onClick="window.location ='<?php echo $_SERVER["HTTP_REFERER"];?>'" />
        </form>
		<br>
		
        <?php  if($build->get_id() > 0){  ?>
          <p><em>Last updated: <?php  echo $build->get_last_updated();  ?> by <?php  echo $build->get_last_updated_user();  ?></em></p>
        <?php  }  ?>			
	
      </div>
    </div> 

</div><!-- /container -->
</div>

<?php  include(INCLUDES . "/footer.php");  ?>
<?php  include(INCLUDES_LIST);  ?>	
<script>
// Include any masks here:
		 //   $("#student_tel").mask("(999) 999-9999");

</script>
  </body>
</html>