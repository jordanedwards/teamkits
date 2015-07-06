<?php 
require("../includes/init.php"); 
$page_id = "club_admin/" . basename(__FILE__);
require(INCLUDES . "/acl_module.php");
include(CLASSES . "/class_orders.php"); 
$activeMenuItem = "Orders";
 
	if(!isset($_GET["id"]) && !isset($_GET["club_id"])) {
		$session->setAlertMessage("Can not edit - the ID is invalid. Please try again.");
		$session->setAlertColor("yellow");
		header("location:" . BASE_URL . "/index.php");
		exit;
	}

	// load the orders		
	$orders_id = $_GET["id"];
	$orders = new Orders();
	
	if ($_GET["id"] ==0){
		// Change this to pass a parent value if creating a new record:
		$orders->set_club_id($_GET['club_id']);
	} else {
		$orders->get_by_id($orders_id);
	}
				
 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
  <?php  include(HEAD);  ?>

    <title><?php   echo $appConfig["app_title"];  ?> | View Order</title>

  </head>
  <body>

<?php  require(INCLUDES . "navbar_club.php");  ?>
<div class="main">

    <div class="container">
      <div class="row">
        <div class="col-md-12">
        <?php  include(INCLUDES . "system_messaging.php");  ?>

        <h1>View Order</h1>
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
	
<table class="admin_table">
	<tr><th colspan="2">Order Details</th></tr>
	<tr>
		<td style="width:1px; white-space:nowrap;">Order Placed: </td>
		<td>
		<?php echo $orders->get_date_created(); ?>									
		</td>
	</tr>
	<tr>
		<td style="width:1px; white-space:nowrap;">Status: </td>
		<td>
		<ol>
			<li <?php if ($orders->get_status() >= 1 && $orders->get_status() != 5){ echo " class='highlight' ";}?> style="padding: 2px;">Received</li>
			<li <?php if ($orders->get_status() >= 2 && $orders->get_status() != 5){ echo " class='highlight' ";}?> style="padding: 2px;">Submitted to Manufacturer</li>
			<li <?php if ($orders->get_status() >= 3 && $orders->get_status() != 5){ echo " class='highlight' ";}?> style="padding: 2px;">Shipped</li>
			<li <?php if ($orders->get_status() >= 4 && $orders->get_status() != 5){ echo " class='highlight' ";}?> style="padding: 2px;">Delivered</li>
		</ol>
			<?php if ($orders->get_status() >= 5){ echo " <p class='highlight'>Cancelled</p>";}?>			
		</td>
	</tr>
		<tr>
		<td style="width:1px; white-space:nowrap;">Notes: </td>
		<td><?php  echo $orders->get_notes();  ?></td>
	</tr>  		
</table>
<br>
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
					echo '<tr><td><a href="orderitem_edit.php?id=' . $row['orderitem_id'] .'"><i class="fa fa-edit fa-lg"></i></a></td><td><a href="item_edit.php?id=' . $row['orderitem_item_number'] . '">' . $row['item_name'] . '</a></td><td>' . $row['orderitem_quantity'] . '</td><td style="white-space:normal">$'.sprintf("%.2f",$row['orderitem_price']) .'</td><td style="white-space:normal">$'.number_format(($row['orderitem_price']*$row['orderitem_quantity']),2) .'</td></tr>';
						$subtotal = $subtotal + number_format(($row['orderitem_price']*$row['orderitem_quantity']),2);
				endwhile;									
			endif;
		 ?>		
			</tbody>
			
			<tfoot>	 	 
			<tr><td colspan="4">Subtotal:</td><td>$<?php echo number_format($subtotal,2);?></td></tr>
			</tfoot>
		</table>
          <br />
          <input type="button" class="btn-default" value="Back" onClick="window.location ='<?php echo $_SERVER["HTTP_REFERER"];?>'" />
		<br>			
	
      </div>
	<div class="col-md-6">
			
			<table class="admin_table">
			<thead>
			<tr><th colspan="4">Payments:<i class="fa fa-credit-card fa-lg add-icon add-payment"></i></th></tr>
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
<script>

$(function() {
	$( "#payment_add_dialog" ).dialog({
	// Select item
		width: 550,	
		modal: true,		
		autoOpen: false,
		show: {
			 effect: "fade",
			duration: 300
		},
		hide: {
			effect: "puff",
			percent:110,
			duration: 200
			},
		buttons: {	
			'Add': {
				click: function() {
					var newItem = $('#newPaymentForm').serialize();
					//console.log(newItem);
						$.ajax({
						url: "ajax/ajax_payment_item.php?"+newItem,	
						success: function (html) {	
							$('#payment_table').append(html);
						}	
					});
               		$(this).dialog('close');
			   	},
			text: "Add",
			class: 'btn btn-primary'			
            },				
			"Cancel": {
				click: function() {
						$( this ).dialog( "close" );
					},
				text: "Cancel",
				class: 'btn btn-primary'
			}
		}
	});

	// Add component click:
	$(".add-payment").on("click", function (e) {
		e.preventDefault();
		$('#payment_add_dialog').dialog('open');
	});
	
	$( "#payment_date" ).datepicker({
		dateFormat: "yy-mm-dd",
		changeMonth: true,
		changeYear: true
	});
});
</script>


<div id="payment_add_dialog" title="Add Payment Record" style="display:none">
<form id="newPaymentForm" method="post">
<h4>Please select:</h4>
 <p>
 <?php
	$dd = New DropDown();
	$dd->set_table("paymentmethod");
	$dd->set_name_field("paymentmethod_title");
	$dd->set_name("payment_method");
	$dd->set_selected_value($student_value);
	$dd->set_class_name("form-control inline");
	$dd->set_active_only(true);
	$dd->set_required(true);	
	$dd->set_placeholder("Payment method");
	$dd->display();	
	?>	  	  
  </p>
 <p>
<input type="number" placeholder="Amount" style="width: 90%;" step="0.01" min="0" class="form-control inline" name="payment_amount"/>
</p>
<p>
<input type="text" name="payment_transaction_number" class="form-control"  placeholder="Transaction #" required/>
</p>
 <p>
 <?php
 	$dd = New DropDown();
	$dd->set_static(true);	
	$dd->set_name("payment_status");	
	$dd->set_required(true);
	$dd->set_class_name("form-control");
		$option_list = "Pending,Completed,Cancelled";
	$dd->set_option_list($option_list);	
	$dd->set_placeholder("Select status");		
	$dd->display();	
	?>	  	  
  </p>
<input type="hidden" name="order_id" value="<?php echo  $orders_id ?>"/>
<input type="hidden" name="action" value="add"/> 
</form>
</div>	

  </body>
</html>