<?php 
require("../includes/init.php"); 
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

    <title><?php   echo $appConfig["app_title"];  ?> | Order Items Edit</title>
  </head>

  <body>

<?php  require(INCLUDES . "navbar.php");  ?>
<div class="main">

    <div class="container">
      <div class="row">
        <div class="col-md-12">
        <?php  include(INCLUDES . "system_messaging.php");  ?>

        <h1><?php if ($_GET["id"] ==0){ ?> Add Order Items<?php  } else { ?> Edit Order Items<?php  } ?></h1>
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
	<form id="form_orderitem" action="actions/action_orderitem_edit.php" method="post">
	<input type="hidden" name="id" value="<?php  echo $orderitem->get_id();  ?>" />
	<input type="hidden" name="action" value="edit" />	
	<input type="hidden" name="page_id" value="<?php  echo $page_id  ?>" />	
	
         <table class="admin_table">
			<tr><th colspan="2">Items:</th></tr>		 
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
	  
	<?php if ($orderitem->get_id()>0): ?>
	<div class="col-md-6">
		<table class="admin_table">
			<thead>
			<tr><th colspan="4">Jersey numbers/names:<i class="fa fa-plus-circle fa-lg add-icon add-item no_print"></i></th></tr>
			<tr><th>Edit/Delete</th><th>Name</th><th>#</th><th>Paid?</th></tr>		
			</thead>
			
			<tbody id="jerseyrecord_table">
		 <?php 
		 	$dm = new DataManager(); 
			$strSQL = "SELECT * from jerseyRecord 
			WHERE orderitem_id=" . $orderitem->get_id() . "
			AND is_active = 'Y'";						

			$result = $dm->queryRecords($strSQL);	
			if ($result):
				while($row = mysqli_fetch_assoc($result)):
					echo '<tr><td><a href="jerseyRecord_edit.php?id=' . $row['id'] .'"><i class="fa fa-edit fa-lg"></i></a>&nbsp;&nbsp;<a href="../actions/action_jerseyRecord_edit.php?action=delete&page_id=jerseyrecord_edit.php&id=' . $row['id'] . '" onclick="return confirm(\'Delete?\');" class="editing"><i class="fa fa-times-circle fa-lg"></i></a></td><td>' . $row['name'] . '</td><td>' . $row['number'] . '</td><td>' . $row['status'] . '</td></tr>';
					if ($row['status']=="paid"){
						$paid ++;
					} else {
						$unpaid ++;
					}
				endwhile;						
			endif;
		 ?>		
			</tbody>
			
			<tfoot>	 	 
			<tr><td colspan="3" style="text-align: right;"># Paid:</td><td style="text-align:right"><?php echo $paid ?></td></tr>
			<tr><td colspan="3" style="text-align: right;"># Unpaid:</td><td style="text-align:right"><?php echo $unpaid ?></td></tr>					
			</tfoot>
		</table>
	</div>
	<?php endif; ?>
    </div> 

</div><!-- /container -->
</div>

<?php  include(INCLUDES . "/footer.php");  ?>
<?php  include(INCLUDES_LIST);  ?>	

<?php include("jerseyrecord_add_dialog.php"); ?>  

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