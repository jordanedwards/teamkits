<?php 
require("includes/init.php"); 
$page_id = basename(__FILE__);
$activeMenuItem = "Brandcatalogue";				
require(INCLUDES . "/acl_module.php");
require_once(CLASSES . "/class_brandcatalogue.php"); 
 
if(!isset($_GET["id"])&& !isset($_GET["brand_id"])) {
	$session->setAlertMessage("Can not edit - the ID is invalid. Please try again.");
	$session->setAlertColor("yellow");
	header("location:" . BASE_URL . "/index.php");
	exit;
}

// load the brandcatalogue		
$brandcatalogue_id = $_GET["id"];
$brandcatalogue = new Brandcatalogue();

if ($_GET["id"] ==0){
	// Change this to pass a parent value if creating a new record:
	$brandcatalogue->set_brand_id($_GET['brand_id']);
	$brandcatalogue->set_is_active("Y");
} else {
	$brandcatalogue->get_by_id($brandcatalogue_id);
}
 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
	<?php  include(HEAD);  ?>
    <meta name="description" content="">

    <title><?php   echo $appConfig["app_title"];  ?> | Catalogue Edit</title>
  </head>

  <body>

<?php  require(INCLUDES . "navbar.php");  ?>
<div class="main">

    <div class="container">
      <div class="row">
        <div class="col-md-12">
        <?php  include(INCLUDES . "system_messaging.php");  ?>

        <h1><?php if ($_GET["id"] ==0){ ?> Add Catalogue<?php  } else { ?> Edit Catalogue<?php  } ?></h1>
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
	<form id="form_brandcatalogue" enctype="multipart/form-data" action="<?php  echo ACTIONS_URL; ?>action_brandcatalogue_edit.php" method="post">
	<input type="hidden" name="id" value="<?php  echo $brandcatalogue->get_id();  ?>" />
	<input type="hidden" name="action" value="edit" />	
	<input type="hidden" name="page_id" value="<?php  echo $page_id  ?>" />	
	
         <table class="admin_table">
				<tr>
           			<td style="width:1px; white-space:nowrap;">Brand: </td>
				
					<td>
					<?php 
						$dd = new DropDown();
						$dd->set_table("brand");	
						$dd->set_name_field("brand_name");
						$dd->set_class_name("form-control inline");
						$dd->set_order("ASC");						
						$dd->set_name("brand_id");						
						$dd->set_selected_value($brandcatalogue->get_brand_id());
						$dd->set_required("true");
						$dd->display();
					 ?>											
					</td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Title: </td>
            		<td><input id="title" name="title" class="form-control inline" type="text"  value="<?php  echo $brandcatalogue->get_title();  ?>"   required /></td>
				</tr>				
				<tr>
           			<td style="width:1px; white-space:nowrap;">Url: </td>
            		<td><input id="url" name="url" class="form-control inline" type="text"  value="<?php  echo $brandcatalogue->get_url();  ?>"   required /></td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Image: </td>
            		<td><!--<input id="image" name="image" class="form-control inline" type="text"  value="<?php  echo $brandcatalogue->get_image();  ?>"  />-->
					<input type="file" name="upload" size="30" placeholder="Upload new image">
					</td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Description: </td>
					<td><textarea id="description" name="description" class="form-control inline" rows="8" ><?php  echo $brandcatalogue->get_description();  ?></textarea></td>
					
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
						$dd->set_selected_value($brandcatalogue->get_is_active());
	
						$dd->display();
					 ?>											
					</td>
				</tr>
  		
		</table>
          <br />
          <input type="submit" class="btn btn-primary" value="<?php if ($brandcatalogue_id ==0){ ?> Add <?php  } else { ?> Save <?php  } ?>" />
          <?php  if ($brandcatalogue_id > 0){  ?>
		  <a href="<?php  echo ACTIONS_URL  ?>action_kit_edit.php?action=delete&page_id=<?php  echo $page_id  ?>&id=<?php  echo $brandcatalogue_id  ?>" onClick="return confirm('You are about to delete this item. Do you want to continue?');" class="btn btn-warning" role="button">Delete</a>
		   <?php  }  ?> 		  
          <input type="button" class="btn btn-default" value="Back" onClick="window.location ='<?php echo $_SERVER["HTTP_REFERER"];?>'" />
        </form>
		<br>
		
        <?php  if($brandcatalogue->get_id() > 0){  ?>
          <p><em>Last updated: <?php  echo $brandcatalogue->get_last_updated();  ?> by <?php  echo $brandcatalogue->get_last_updated_user();  ?></em></p>
        <?php  }  ?>			
	
      </div>
	 <div class="col-md-6">
	 <?php if ($brandcatalogue->get_image() != ""):  ?>
	 <img src="/img/brands/<?php echo strtolower($brandcatalogue->get_brand_name()) . "/". $brandcatalogue->get_image();  ?>">
	 <?php endif; ?>
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