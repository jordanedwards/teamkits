<?php 
require("includes/init.php"); 
$page_id = basename(__FILE__);
require(INCLUDES . "/acl_module.php");
include(CLASSES . "/class_promo.php"); 
 
	if(!isset($_GET["id"])) {
		$session->setAlertMessage("Can not edit - the ID is invalid. Please try again.");
		$session->setAlertColor("yellow");
		header("location:" . BASE_URL . "/index.php");
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
$activeMenuItem = "Promo";				
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
	<div class="col-md-6">
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
						$dd->set_class_name("form-control inline");
						$dd->set_order("ASC");						
						$dd->set_name("promo_sport");						
						$dd->set_selected_value($promo->get_sport());
						$dd->set_required("true");						
						$dd->display();
					 ?>	
					 </td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Item id: </td>
				
					<td>
					<?php 
						$dd = new DropDown();
						$dd->set_table("item");	
						$dd->set_name_field("item_name");
						$dd->set_class_name("form-control inline");
						$dd->set_order("ASC");						
						$dd->set_name("promo_item_id");						
						$dd->set_selected_value($promo->get_item_id());
						$dd->set_required("true");
						$dd->display();
					 ?>											
					</td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Title: </td>
            		<td><input id="promo_title" name="promo_title" type="text"  value="<?php  echo $promo->get_title();  ?>" style="width:90%"  class="{validate:{required:true}}" /> <span class='red'>*</span> </td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Description: </td>
					<td><textarea id="promo_description" name="promo_description" style="width:90%" rows="8" ><?php  echo $promo->get_description();  ?></textarea></td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">View type: </td>

					<td>
					<?php 					
						$dd = new DropDown();
						$dd->set_static(true);	
						$dd->set_name("promo_view_type");
						$dd->set_class_name("form-control inline");
						$dd->set_option_list("All,Brand,Club Exclusive");						
						$dd->set_selected_value($promo->get_view_type());
						$dd->set_required("true");						
						$dd->display();
					 ?>						
					</td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Club: </td>
				
					<td>
					<?php 
						$dd = new DropDown();
						$dd->set_table("club");	
						$dd->set_name_field("club_name");
						$dd->set_class_name("form-control inline");
						$dd->set_order("ASC");						
						$dd->set_name("promo_club_id");						
						$dd->set_selected_value($promo->get_club_id());
						$dd->display();
					 ?>
					 <p class="small">* Only fill this field in if this promo is Club Exclusive</p>
					</td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Price: </td>
					<td>$<input id="promo_price" name="promo_price" type="number" step=".01" value="<?php  echo $promo->get_price();  ?>"  style="width:90%" /></td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Image: </td>
            		<td><input id="promo_image" name="promo_image" type="text"  value="<?php  echo $promo->get_image();  ?>" style="width:90%" />
					<p class="small">* PROMOTIONAL graphic, if desired, not the item image.</p>
					</td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Expiry: </td>
            		<td><input id="promo_expiry" name="promo_expiry" type="text"  value="<?php  echo $promo->get_expiry();  ?>" style="width:90%" /> </td>
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
						$dd->set_selected_value($promo->get_active());
						$dd->set_required("true");						
						$dd->display();
					 ?>	
					</td>
				</tr>
  		
		</table>
          <br /> 
          <input type="submit" class="btn-success" value="<?php if ($_GET["id"] ==0){ ?> Add <?php  } else { ?> Save <?php  } ?>" />&nbsp;&nbsp;
          <input type="button" class="btn-default" value="Cancel" onClick="window.location ='<?php echo $_SERVER["HTTP_REFERER"];?>'" />
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

$(document).ready(function() {
	$("#price").mask("999999999999.99"); 
});	

</script>
<script type="text/javascript">
	$( "#promo_expiry" ).datepicker({
		dateFormat: "yy-mm-dd",
		changeMonth: true,
		changeYear: true
	});

// Include any masks here:
		 //   $("#student_tel").mask("(999) 999-9999");
		
  </script>		
  </body>
</html>