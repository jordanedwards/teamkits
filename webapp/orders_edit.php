<?php 
require("includes/init.php"); 
$page_id = basename(__FILE__);
require(INCLUDES . "/acl_module.php");
include(CLASSES . "/class_orders.php"); 
$activeMenuItem = "Orders";
 
	if(!isset($_GET["id"])) {
		$session->setAlertMessage("Can not edit - the ID is invalid. Please try again.");
		$session->setAlertColor("yellow");
		header("location:" . $base_href . "/index.php");
		exit;
	}

	// load the orders		
	$orders_id = $_GET["id"];
	$orders = new Orders();
	
	if ($_GET["id"] ==0){
		// Change this to pass a parent value if creating a new record:
		//	$orders->set_customer_id($_GET['customer_id']);
	} else {
		$orders->get_by_id($orders_id);
	}
				
 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
  <?php  include(HEAD);  ?>

    <title><?php   echo $appConfig["app_title"];  ?> | Orders Edit</title>

  </head>

  <body>

<?php  require(INCLUDES . "navbar.php");  ?>
<div class="main">

    <div class="container">
      <div class="row">
        <div class="col-md-12">
        <?php  include(INCLUDES . "system_messaging.php");  ?>

        <h1><?php if ($_GET["id"] ==0){ ?> Add Orders<?php  } else { ?> Edit Orders<?php  } ?></h1>
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
	<form id="form_orders" action="<?php  echo ACTIONS_URL; ?>action_orders_edit.php" method="post">
	<input type="hidden" name="orders_id" value="<?php  echo $orders->get_id();  ?>" />
	<input type="hidden" name="action" value="edit" />	
	<input type="hidden" name="page_id" value="<?php  echo $page_id  ?>" />	
	
         <table class="admin_table">
		 	<tr><th colspan="2">Club details</th></tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Club:</td>
				
					<td>
					<?php 
						$dd = new DropDown();
						$dd->set_table("club");	
						$dd->set_name_field("club_name");
						$dd->set_class_name("form-control");
						$dd->set_order("ASC");						
					//	$dd->set_preset("supplier");
						$dd->set_name("order_club_id");						
						$dd->set_selected_value($orders->get_club_id());
						$dd->display();
					 ?>											
					
					</td>
				</tr>
			<!--	<tr>
           			<td style="width:1px; white-space:nowrap;">Customer: </td>
            		<td><input id="order_customer" name="order_customer" type="number" step="any" value="<?php  echo $orders->get_customer();  ?>" style="width:90%" /> </td>
				</tr>-->
			<!--	<tr>
           			<td style="width:1px; white-space:nowrap;">Item: </td>
				
					<td>
					<?php 
						$dd = new DropDown();
						$dd->set_table("item");	
						$dd->set_name_field("item_name");
						$dd->set_class_name("form-control");
						$dd->set_order("ASC");						
						//$dd->set_preset("supplier");
						$dd->set_name("order_item");						
						$dd->set_selected_value($orders->get_item());
						$dd->display();
					 ?>											
					
					</td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Price: </td>
            		<td><input id="order_price" name="order_price" type="text"  value="<?php  echo $orders->get_price();  ?>" style="width:90%" /> </td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Quantity: </td>
            		<td><input id="order_quantity" name="order_quantity" type="number" step="any" value="<?php  echo $orders->get_quantity();  ?>" style="width:90%" /> </td>
				</tr>-->
				<tr>
           			<td style="width:1px; white-space:nowrap;">Status: </td>
				
					<td>
					<?php 
						$dd = new DropDown();
						$dd->set_table("orderstatus");	
						$dd->set_name_field("orderstatus_title");
						$dd->set_class_name("form-control");
						$dd->set_order_by("orderstatus_order");							
						$dd->set_order("ASC");						
						$dd->set_name("order_status");	
						$dd->set_selected_value($orders->get_status());
						$dd->display();
					 ?>											
					
					</td>
				</tr>
			<!--	<tr>
           			<td style="width:1px; white-space:nowrap;">Tracking number: </td>
            		<td><input id="order_tracking_number" name="order_tracking_number" type="text"  value="<?php  echo $orders->get_tracking_number();  ?>" style="width:90%" /> </td>
				</tr>-->
				<tr>
           			<td style="width:1px; white-space:nowrap;">Notes: </td>
            		<td><textarea id="order_notes" name="order_notes" style="width:90%" rows="4"><?php  echo $orders->get_notes();  ?></textarea></td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Active: </td>

					<td>
					<?php 					
						$dd = new DropDown();
						$dd->set_static(true);	
						$dd->set_name("is_active");
						$dd->set_class_name("form-control");
						$dd->set_option_list("Y,N");						
						$dd->set_selected_value($orders->get_active());
						$dd->display();
					 ?>						
					</td>
				</tr>
  		
		</table>
          <br />
          <input type="submit" value="Add/Update Orders" />&nbsp;&nbsp;
          <input type="button" value="Cancel" onClick="window.location ='<?php echo $_SERVER["HTTP_REFERER"];?>'" />
        </form>
		<br>
		
        <?php  if($orders->get_id() > 0){  ?>
          <p><em>Last updated: <?php  echo $orders->get_last_updated();  ?> by <?php  echo $orders->get_last_updated_user();  ?></em></p>
        <?php  }  ?>			
	
      </div>
	<div class="col-md-6">
		<?php if ($orders_id > 0): ?>
			<table class="admin_table">
			<thead>
			<tr><th colspan="5">Order Items:<i class="fa fa-plus-circle fa-lg add-icon add-item"></i></th></tr>
			<tr><th></th><th>Item:</th><th>Quantity</th><th>Price</th><th>Total</th></tr>	
			</thead>
			
			<tbody id="order_items_table">
		 <?php 
		 	$dm = new DataManager(); 
			$strSQL = "SELECT * from orderitem 
			LEFT JOIN item ON orderitem.orderitem_item_number = item.item_id 
			WHERE orderitem.orderitem_order_id=" . $orders->get_id() . "
			AND orderitem.is_active = 'Y'";						

			$result = $dm->queryRecords($strSQL);	
			if ($result):
				while($row = mysqli_fetch_assoc($result)):
					echo '<tr><td><a href="orderitem_edit.php?id=' . $row['orderitem_id'] .'"><i class="fa fa-edit fa-lg"></i></a></td><td>' . $row['item_name'] . '</td><td>' . $row['orderitem_quantity'] . '</td><td style="white-space:normal">$'.sprintf("%.2f",$row['orderitem_price']) .'</td><td style="white-space:normal">$'.number_format(($row['orderitem_price']*$row['orderitem_quantity']),2) .'</td></tr>';
						$subtotal = $subtotal + number_format(($row['orderitem_price']*$row['orderitem_quantity']),2);
				endwhile;									
			endif;
		 ?>		
			</tbody>
			
			<tfoot>	 	 
			<tr><td colspan="4">Subtotal:</td><td>$<?php echo number_format($subtotal,2);?></td></tr>
			</tfoot>
		</table>
			<table class="admin_table">
			<thead>
			<tr><th colspan="4">Payments:<i class="fa fa-plus-circle fa-lg add-icon add-payment"></i></th></tr>
			<tr><th></th><th>Amount</th><th>Method</th><th>Date</th></tr>	
			</thead>
			
			<tbody id="payment_table">
		 <?php 
		 	$dm = new DataManager(); 
			$strSQL = "SELECT * from payment 
			LEFT JOIN paymentmethod ON payment.payment_method = paymentmethod.paymentmethod_id
			WHERE payment.payment_order_id=" . $orders->get_id() . "
			AND payment.is_active = 'Y'";						

			$result = $dm->queryRecords($strSQL);	
			if ($result):
				while($row = mysqli_fetch_assoc($result)):
					echo '<tr><td><a href="payment_edit.php?id=' . $row['payment_id'] .'"><i class="fa fa-edit fa-lg"></i></a></td><td>' . number_format($row['payment_amount'],2) . '</td><td>' . $row['paymentmethod_title'] . '</td><td style="white-space:normal">'.$row['payment_date_created'] .'</td></tr>';
					$payment_total = $payment_total + $row['payment_amount'];
				endwhile;									
			endif;
		 ?>		
			</tbody>
			<?php $total = $subtotal - $payment_total; ?>
			<tfoot>	 	 
			<tr><td colspan="3">Balance:</td><td id="total" style="text-align:right">$<?php echo number_format($total,2);?></td></tr>
			</tfoot>			
		</table>
			<br>
			<table class="admin_table">
			<thead>
			<tr><th colspan="5">Shipping details:<i class="fa fa-plus-circle fa-lg add-icon add-shipping"></i></th></tr>
			<tr><th></th><th>Date</th><th>Carrier</th><th>Tracking #</th></tr>	
			</thead>
			
			<tbody id="shippingrecord_table">
		 <?php 
		 	$dm = new DataManager(); 
			$strSQL = "SELECT * from shippingrecord 
			WHERE shippingrecord.shippingrecord_order_id=" . $orders->get_id() . "
			AND shippingrecord.is_active = 'Y'";						

			$result = $dm->queryRecords($strSQL);	
			if ($result):
				while($row = mysqli_fetch_assoc($result)):
					echo '<tr><td><a href="shippingrecord_edit.php?id=' . $row['shippingrecord_id'] .'"><i class="fa fa-edit fa-lg"></i></a></td><td>' . $row['shippingrecord_date'] . '</td><td>' . $row['shippingrecord_carrier'] . '</td><td style="white-space:normal">'.$row['shippingrecord_tracking'] .'</td></tr>';
				endwhile;									
			endif;
		 ?>		
			</tbody>
			
		</table>
		<?php endif ?>
	</div>
    </div> 

</div><!-- /container -->
</div>

<?php  include(INCLUDES . "/footer.php");  ?> 
<?php require(INCLUDES_LIST);?>	

	
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
<?php include(SCRIPTS . "order_item_add_dialog.php"); ?>  
<?php include(SCRIPTS . "shipping_add_dialog.php"); ?>  
<?php include(SCRIPTS . "payment_add_dialog.php"); ?>  

  </body>
</html>