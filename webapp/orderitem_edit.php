<?php 
require("includes/init.php"); 
$page_id = basename(__FILE__);
require(INCLUDES . "/acl_module.php");
include(CLASSES . "/class_orderitem.php"); 
 
	if(!isset($_GET["id"])) {
		$session->setAlertMessage("Can not edit - the ID is invalid. Please try again.");
		$session->setAlertColor("yellow");
		header("location:" . $base_href . "/index.php");
		exit;
	}

	// load the orderitem		
	$orderitem_id = $_GET["id"];
	$orderitem = new Orderitem();
	
	if ($_GET["id"] ==0){
		// Change this to pass a parent value if creating a new record:
		//	$orderitem->set_customer_id($_GET['customer_id']);
	} else {
		$orderitem->get_by_id($orderitem_id);
	}
$activeMenuItem = "Orderitem";				
 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
	<?php  include(HEAD);  ?>
    <meta name="description" content="">

    <title><?php   echo $appConfig["app_title"];  ?> | Order Item Edit</title>
  </head>

  <body>

<?php  require(INCLUDES . "navbar.php");  ?>
<div class="main">

    <div class="container">
      <div class="row">
        <div class="col-md-12">
        <?php  include(INCLUDES . "system_messaging.php");  ?>

        <h1><?php if ($_GET["id"] ==0){ ?> Add Order Item<?php  } else { ?> Edit Order Item<?php  } ?></h1>
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
	<form id="form_orderitem" action="<?php  echo ACTIONS_URL; ?>action_orderitem_edit.php" method="post">
	<input type="hidden" name="orderitem_id" value="<?php  echo $orderitem->get_id();  ?>" />
	<input type="hidden" name="action" value="edit" />	
	<input type="hidden" name="page_id" value="<?php  echo $page_id  ?>" />	
	
         <table class="admin_table">
				<tr>
           			<td style="width:1px; white-space:nowrap;">Item: </td>
				
					<td>
					<?php 
						$dd = new DropDown();
						$dd->set_table("item");	
						$dd->set_name_field("item_name");
						$dd->set_class_name("form-control inline");
						$dd->set_order("ASC");						
						$dd->set_name("orderitem_item_number");						
						$dd->set_selected_value($orderitem->get_item_number());
						$dd->set_required(true);
						$dd->display();
					 ?>	
					 </td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Price: </td>
            		<td>$<input id="orderitem_price" name="orderitem_price" type="number" step="any" value="<?php  echo $orderitem->get_price();  ?>" style="width:90%" /> </td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Quantity: </td>
            		<td><input id="orderitem_quantity" name="orderitem_quantity" type="number" step="1" min="0" value="<?php  echo $orderitem->get_quantity();  ?>" style="width:90%" /> </td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Size: </td>
					<td>
					<?php 
						$dd = new DropDown();
						$dd->set_table("itemSize");	
						$dd->set_name_field("itemSize_name");
						$dd->set_class_name("form-control inline");
						$dd->set_index_name("itemSize_name");
						$dd->set_order("ASC");						
						$dd->set_name("orderitem_size");						
						$dd->set_selected_value($orderitem->get_size());
						$dd->set_where("itemSize_item_id = ".$orderitem->get_item_number());
						$dd->set_active_only(false);
						$dd->display();
					 ?>	
					 </td>
				</tr>				
				<tr>
           			<td style="width:1px; white-space:nowrap;">Discount: </td>
            		<td><input id="orderitem_discount" name="orderitem_discount" type="number" step=".01" max="1" min="0" value="<?php  echo $orderitem->get_discount();  ?>" style="width:90%" /> </td>
				</tr>
  		
		</table>
          <br />
          <input type="submit" class="btn btn-success" value="<?php if ($_GET["id"] ==0){ ?> Add <?php  } else { ?> Save <?php  } ?>" />&nbsp;
          <input type="submit" class="btn btn-warning"  value="Delete" name="delete"/>&nbsp;	  		  
          <input type="button" class="btn btn-default" value="Back" onClick="window.location ='<?php echo $_SERVER["HTTP_REFERER"];?>'" />
        </form>
		<br>
		
        <?php  if($orderitem->get_id() > 0){  ?>
          <p><em>Last updated: <?php  echo $orderitem->get_last_updated();  ?> by <?php  echo $orderitem->get_last_updated_user();  ?></em></p>
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