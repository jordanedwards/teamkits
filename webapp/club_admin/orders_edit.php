<?php 
require("../includes/init.php"); 
$page_id = "club_admin/" . basename(__FILE__);
require(INCLUDES . "/acl_module.php");
include_once(CLASSES . "/class_club.php"); 
$club = new Club();	 
$club->get_by_user_id($currentUser->get_id()); 

include(CLASSES . "/class_orders.php"); 
$activeMenuItem = "Orders";
 
	if( !isset($_GET["id"]) || $club->get_id()==0 ) {
		$session->setAlertMessage("Can not edit - the ID is invalid. Please try again.");
		$session->setAlertColor("yellow");
		header("location:" . BASE_URL . "/index.php");
		exit;
	}

	// load the orders		
	$orders = new Orders();
	
	if ($_GET["id"] ==0){
		// Change this to pass a parent value if creating a new record:
		$orders->set_club_id($club->get_id());
		$orders->set_status(1);		
	} else {
		$orders->get_by_id($_GET["id"]);
	}

	if ($orders->get_status() !=1){
	// If timesheet is submitted or archived and viewer is foreman, do not allow editing:
		$block_editing = true;
	}
	
	if (isset($_GET['kit']) && $_GET['kit'] > 0){
		// Add a kit to an order
		// Must make sure that the order is saved first, or the orderitems will get attached to nothing:
		if ($orders->get_id() == 0){
			// Save
			$orders->save();
		}
		$kit = $_GET['kit'];
		$orders->add_kit($kit);
	}			
 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
  <?php  include(HEAD);  ?>

    <title><?php   echo $appConfig["app_title"];  ?> | View Order</title>
	<link href="../css/carousel.css" rel="stylesheet">    

<style>
#preview_window{
  width: 100%;
  height: 400px;
  border: 2px solid #222;
  background: url('/images/mockup_preview.gif') #ccc;
  background-repeat: no-repeat;
  background-position: center;
}
.imagePreview{
cursor:pointer;
  background: #fff;
  padding: 5px;
}
.imagePreview:hover{
	text-decoration:none;
}
</style>

  </head>
  <body>
<?php  require(INCLUDES . "navbar.php");  ?>
<div class="main">

    <div class="container">
      <div class="row">
        <div class="col-md-12">
        <?php  include(INCLUDES . "system_messaging.php");  ?>

        <h1><?php if($orders->get_status() == 1){ echo "Create";} else{ echo "View";}?> Order</h1>
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
			<li <?php if ($orders->get_status() >= 1 && $orders->get_status() != 6){ echo " class='highlight' ";}?> style="padding: 2px;">Open</li>
			<li <?php if ($orders->get_status() >= 2 && $orders->get_status() != 6){ echo " class='highlight' ";}?> style="padding: 2px;">Submitted</li>			
			<li <?php if ($orders->get_status() >= 3 && $orders->get_status() != 6){ echo " class='highlight' ";}?> style="padding: 2px;">Sent to Manufacturer</li>
			<li <?php if ($orders->get_status() >= 4 && $orders->get_status() != 6){ echo " class='highlight' ";}?> style="padding: 2px;">Shipped</li>
			<li <?php if ($orders->get_status() >= 5 && $orders->get_status() != 6){ echo " class='highlight' ";}?> style="padding: 2px;">Delivered</li>
		</ol>
			<?php if ($orders->get_status() >= 6){ echo " <p class='highlight'>Cancelled</p>";}?>			
		</td>
	</tr>
		<tr>
		<td style="width:1px; white-space:nowrap;">Description: </td>
		<td>
		<p class="static"><?php  echo $orders->get_notes();  ?></p></td>
	</tr>  		
</table>
<br>
<table class="admin_table">
			<thead>
			<tr><th colspan="6">Order Items:<i class="fa fa-plus-circle fa-lg add-icon add-item"></i></th></tr>
			<tr><th></th><th>Item:</th><th>#</th><th>Size</th><th>Price</th><th>Total</th></tr>		
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
					echo '<tr><td><a href="orderitem_edit.php?id=' . $row['orderitem_id'] .'"><i class="fa fa-edit fa-lg"></i></a></td><td><a class="imagePreview" data-item-id="' . $row['orderitem_item_number'] . '">' . $row['item_name'] . '</a></td><td>' . $row['orderitem_quantity'] . '</td><td>' . $row['orderitem_size'] . '</td><td style="white-space:normal; text-align:right;">$'.sprintf("%.2f",$row['orderitem_price']) .'</td><td style="white-space:normal; text-align:right ">$'.number_format(($row['orderitem_price']*$row['orderitem_quantity']),2) .'</td></tr>';
					$order_items = $order_items +$row['orderitem_quantity'];
				endwhile;						
			endif;
		 ?>		
			</tbody>
			
			<tfoot>	 	 
			<tr><td colspan="5" style="text-align: right;">Subtotal:</td><td style="text-align:right">$<?php echo number_format($orders->get_subtotal(),2);?></td></tr>
			<tr><td colspan="5" style="text-align: right;">Discount:</td><td style="text-align:right">-$<?php echo number_format($orders->get_discount(),2);?></td></tr>			
			<tr><td colspan="5" style="text-align: right;">Tax:</td><td style="text-align:right">$<?php echo number_format($orders->get_tax(),2);?></td></tr>
			<tr><td colspan="5" style="text-align: right;"><strong>Total:</strong></td><td style="text-align:right"><strong>$<?php echo number_format($orders->get_total(),2);?></strong></td></tr>			
			</tfoot>
		</table>
          <br />
		  <table class="admin_table">
			<thead>
			<tr><th colspan="5">Payments:</th></tr>
			<tr><th>Amount</th><th>Method</th><th>Txn Id</th><th>Date</th></tr>	
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
					echo '<tr><td>' . number_format($row['payment_amount'],2) . '</td><td>' . $row['paymentmethod_title'] . '</td><td>' . $row['payment_transaction_number'] . '</td><td style="white-space:normal">'.substr($row['payment_date_created'],0,10) .'</td></tr>';
					$payment_total = $payment_total + $row['payment_amount'];
				endwhile;									
			endif;
		 ?>		
			</tbody>
			<?php $total = $orders->get_total() - $payment_total; ?>
			<tfoot>	 	 
			<tr><td colspan="3">Balance:</td><td id="total" style="text-align:right">$<?php echo number_format($total,2);?></td></tr>
			</tfoot>			
		</table>
		<br>
		<?php if($orders->get_status() == 1){ ?>
		<form action="" method="POST" style="display: inline-block;">
			  <script
				src="https://checkout.stripe.com/checkout.js" class="stripe-button"
				data-key="pk_test_W35SjiDSgEmsbm4bh9xWhIrN"
				data-amount="<?php echo $orders->get_total()*100 ?>"
				data-name="Order #<?php echo $orders->get_id() ?>"
				data-description="<?php echo $order_items ?> items - ($<?php echo $orders->get_total() ?>)"
				data-image="/images/128x128.png"
				data-email="jordan@orchardcity.ca"
				data-customer="16">
			  </script>
			</form>
		 <!-- <input type="submit" class="btn btn-success" value="Submit Order" />-->
		   <a href="<?php  echo ACTIONS_URL  ?>action_orders_edit.php?action=delete&page_id=<?php  echo $page_id  ?>&id=<?php  echo $orders->get_id()  ?>" onClick="return confirm('Cancel this order?');" class="btn btn-warning" role="button">Cancel Order</a>		  
		  <?php } ?>
          <input type="button" class="btn btn-default" value="Back" onClick="window.location ='<?php echo $_SERVER["HTTP_REFERER"];?>'" />
		  
		<br>			
	
      </div>
	<div class="col-md-6">
			<div id="preview_window">
			
			</div>
			
			<br>
			<table class="admin_table">
			<thead>
			<tr><th colspan="5">Shipping details:</th></tr>
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
<?php include("order_item_add_dialog.php"); ?>  
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
	
	$(".imagePreview").on("click", function (e) {
		e.preventDefault();
		var orderItem = $(this).data("item-id");
		 
		$.ajax({
			url: "ajax_image_carousel.php?id="+orderItem,	
			success: function (html) {	
			  $('#preview_window').html(html);
			}		
		});
	});


});
</script>


<div id="payment_add_dialog" title="Add Payment Record" style="display:none">
<form action="" method="POST">
  <script
    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
    data-key="pk_test_W35SjiDSgEmsbm4bh9xWhIrN"
    data-amount="2000"
    data-name="Demo Site"
    data-description="2 widgets ($20.00)"
    data-image="/128x128.png">
  </script>
</form>
</div>	

  </body>
<script>
	<?php if ($block_editing){ ?>
	$('.editing').hide();
	$('.static').show();
	<?php } ?>
</script>
</html>