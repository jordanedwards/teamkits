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
	$order = new Orders();
	
	if ($_GET["id"] ==0){
		// Change this to pass a parent value if creating a new record:
		$order->set_club_id($club->get_id());
		$order->set_status(1);
		$order->save();	
	} else {
		$order->get_by_id($_GET["id"]);
	}

	if( $club->get_id() !=  $order->get_club_id()) {
	// Block user from viewing other user's orders:
		$session->setAlertMessage("Access denied: This order belongs to a different club");
		$session->setAlertColor("red");
		header("location:" . BASE_URL . "/index.php");
		exit;
	}
	
	if ($order->get_status() !=1){
	// Do not allow editing if order has been submitted:
		$block_editing = true;
	}
	
	if (isset($_GET['kit']) && $_GET['kit'] > 0){
		// Add a kit to an order
		// Must make sure that the order is saved first, or the orderitems will get attached to nothing:
		if ($order->get_id() == 0){
			// Save
			$order->save();
		}
		$kit = $_GET['kit'];
		$order->add_kit($kit);
	}
// Process stripe payment 
if (isset($_POST['stripeToken'])){
	require("../stripe/stripe-php-2.3.0/init.php");
	require(CLASSES . "/class_payment.php"); 

	// Set your secret key: remember to change this to your live secret key in production
	// See your keys here https://dashboard.stripe.com/account/apikeys

	\Stripe\Stripe::setApiKey(STRIPE_API_KEY);

	// Get the credit card details submitted by the form
	$token = $_POST['stripeToken'];

	// Create the charge on Stripe's servers - this will charge the user's card
	try {
		$charge = \Stripe\Charge::create(array(
		  "amount" => $_POST['payment_amount'], // amount in cents, again
		  "currency" => "cad",
		  "source" => $token,
		  "description" => "Example charge")
		);
		$amount = $_POST['payment_amount']/100;
		//Payment processed, add record
		$new_payment = new Payment();
		$new_payment->set_order_id($order->get_id());
		$new_payment->set_transaction_number($token);
		$new_payment->set_amount($amount);
		$new_payment->set_method('3');
		$new_payment->set_status('Completed');
		$new_payment->set_active('Y');
		$new_payment->save();
		
		// If full payment has been made and status was "Open", run submitted procedure
		$order->submit();
					
		$session->setAlertMessage("Payment processed, thank you. Your order has been submitted");
		$session->setAlertColor("green");
	} catch(\Stripe\Error\Card $e) {
	  // The card has been declined
		$session->setAlertMessage("Credit card payment failed. Please try again.");
		$session->setAlertColor("red");
	}	
}
 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
  <?php  include(HEAD);  ?>

    <title><?php   echo $appConfig["app_title"];  ?> | View Order </title>
	<link href="../css/carousel.css" rel="stylesheet">    
	<link href="../css/print.css" rel="stylesheet" media="print">    
	
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
.fa-right {
	float: right;
    margin-right: 20px;
    margin-top: 5px;
    cursor: pointer;
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

        <h1><?php if($order->get_status() == 1){ echo "Create Order";} else{ echo "Order #" . $order->get_id();}?> <i class="fa fa-print fa-right no_print"></i></h1>
        <br>
		</div>
	</div>
	
	

	<div class="row">
	<div class="col-md-6">
	
<table class="admin_table">
	<tr><th colspan="2">Order Details</th></tr>
<?php if($order->get_status() != 1){ ?>	<tr>
		<td style="width:1px; white-space:nowrap;">Order Placed: </td>
		<td>
		<?php echo substr($order->get_date_submitted(),0,10); ?>									
		</td>
	</tr>
<?php } ?>
	<tr>
		<td style="width:1px; white-space:nowrap;">Status: </td>
		<td>
		<ol>
			<li <?php if ($order->get_status() >= 1 && $order->get_status() != 6){ echo " class='highlight' ";}?> style="padding: 2px;">Open</li>
			<li <?php if ($order->get_status() >= 2 && $order->get_status() != 6){ echo " class='highlight' ";}?> style="padding: 2px;">Submitted</li>			
			<li <?php if ($order->get_status() >= 3 && $order->get_status() != 6){ echo " class='highlight' ";}?> style="padding: 2px;">Sent to Manufacturer</li>
			<li <?php if ($order->get_status() >= 4 && $order->get_status() != 6){ echo " class='highlight' ";}?> style="padding: 2px;">Shipped</li>
			<li <?php if ($order->get_status() >= 5 && $order->get_status() != 6){ echo " class='highlight' ";}?> style="padding: 2px;">Delivered</li>
		</ol>
			<?php if ($order->get_status() >= 6){ echo " <p class='highlight'>Cancelled</p>";}?>			
		</td>
	</tr>
		<tr>
		<td style="width:1px; white-space:nowrap;">Description: </td>
		<td>
		<p class="static"><?php  echo $order->get_notes();  ?></p></td>
	</tr>  		
</table>
<br>
<table class="admin_table">
			<thead>
			<tr><th colspan="6">Order Items:<i class="fa fa-plus-circle fa-lg add-icon add-item no_print"></i></th></tr>
			<tr><th ></th><th>Item:</th><th>#</th><th>Size</th><th>Price</th><th>Total</th></tr>		
			</thead>
			
			<tbody id="order_items_table">
		 <?php 
		 	$dm = new DataManager(); 
			$strSQL = "SELECT * from orderitem 
			LEFT JOIN item ON orderitem.orderitem_item_number = item.item_id 
			WHERE orderitem.orderitem_order_id=" . $order->get_id() . "
			AND orderitem.is_active = 'Y'";						

			$result = $dm->queryRecords($strSQL);	
			if ($result):
				while($row = mysqli_fetch_assoc($result)):
					echo '<tr><td><a href="orderitem_edit.php?id=' . $row['orderitem_id'] .'"><i class="fa fa-edit fa-lg"></i></a>&nbsp;&nbsp;<a href="actions/action_orderitem_edit.php?action=delete&page_id=orderitem_edit.php&id=' . $row['orderitem_id'] . '" onclick="return confirm(\'You are about to delete this item. Continue?\');" class="editing"><i class="fa fa-times-circle fa-lg"></i></a></td><td><a class="imagePreview" data-item-id="' . $row['orderitem_item_number'] . '">' . $row['item_name'] . '</a></td><td>' . $row['orderitem_quantity'] . '</td><td>' . $row['orderitem_size'] . '</td><td style="white-space:normal; text-align:right;">$'.sprintf("%.2f",$row['orderitem_price']) .'</td><td style="white-space:normal; text-align:right ">$'.number_format(($row['orderitem_price']*$row['orderitem_quantity']),2) .'</td></tr>';
					$order_items = $order_items +$row['orderitem_quantity'];
				endwhile;						
			endif;
		 ?>		
			</tbody>
			
			<tfoot>	 	 
			<tr><td colspan="5" style="text-align: right;">Subtotal:</td><td style="text-align:right">$<?php echo number_format($order->get_subtotal(),2);?></td></tr>
			<tr><td colspan="5" style="text-align: right;">Discount:</td><td style="text-align:right">-$<?php echo number_format($order->get_discount(),2);?></td></tr>			
			<tr><td colspan="5" style="text-align: right;">Tax:</td><td style="text-align:right">$<?php echo number_format($order->get_tax(),2);?></td></tr>
			<tr><td colspan="5" style="text-align: right;"><strong>Total:</strong></td><td style="text-align:right"><strong>$<?php echo number_format($order->get_total(),2);?></strong></td></tr>			
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
			WHERE payment.payment_order_id=" . $order->get_id() . "
			AND payment.is_active = 'Y'";						

			$result = $dm->queryRecords($strSQL);	
			if ($result):
				while($row = mysqli_fetch_assoc($result)):
					echo '<tr><td>$' . number_format($row['payment_amount'],2) . '</td><td>' . $row['paymentmethod_title'] . '</td><td>' . $row['payment_transaction_number'] . '</td><td style="white-space:normal">'.substr($row['payment_date_created'],0,10) .'</td></tr>';
					$payment_total = $payment_total + $row['payment_amount'];
				endwhile;									
			endif;
		 ?>		
			</tbody>
			<?php $total = $order->get_total() - $payment_total; ?>
			<tfoot>	 	 
			<tr><td colspan="3">Balance:</td><td id="total" style="text-align:right">$<?php echo number_format($total,2);?></td></tr>
			</tfoot>			
		</table>
		<br>
		<?php if($order->get_status() == 1){ ?>
		
		<?php if($total > 0){ ?>
		<form action="" method="POST" style="display: inline-block;">
			<input type="hidden" name="payment_amount" value="<?php echo $total *100 ?>">
			  <script
				src="https://checkout.stripe.com/checkout.js" class="stripe-button"
				data-key="<?php echo STRIPE_API_KEY_PUBLIC ?>"
				data-amount="<?php echo $total*100 ?>"
				data-name="Order #<?php echo $order->get_id() ?>"
				data-description="<?php echo $order_items ?> items - ($<?php echo number_format($total,2) ?>)"
				data-image="/images/128x128.png">
			  </script>
			</form>
		<?php } ?>
		 <!-- <input type="submit" class="btn btn-success" value="Submit Order" />-->
		   <a href="<?php  echo ACTIONS_URL  ?>action_orders_edit.php?action=delete&page_id=<?php  echo $page_id  ?>&id=<?php  echo $order->get_id()  ?>" onClick="return confirm('Cancel this order?');" class="btn btn-warning" role="button">Cancel Order</a>		  
		  <?php } ?>
          <input type="button" class="btn btn-default" value="Back" onClick="window.location ='<?php echo $_SERVER["HTTP_REFERER"];?>'" />
		  
		<br>			
	
      </div>
	<div class="col-md-6">
			<div id="preview_window" class="no_print">
			
			</div>
			
			<br>
			<table class="admin_table">
			<thead>
			<tr><th colspan="5">Shipping details:</th></tr>
			<tr><th>Date</th><th>Carrier</th><th>Tracking #</th></tr>	
			</thead>
			
			<tbody id="shippingrecord_table">
		 <?php 
		 	$dm = new DataManager(); 
			$strSQL = "SELECT * from shippingrecord 
			WHERE shippingrecord.shippingrecord_order_id=" . $order->get_id() . "
			AND shippingrecord.is_active = 'Y'";						

			$result = $dm->queryRecords($strSQL);	
			if ($result):
				while($row = mysqli_fetch_assoc($result)):
					echo '<tr><td>' . $row['shippingrecord_date'] . '</td><td>' . $row['shippingrecord_carrier'] . '</td><td style="white-space:normal">'.$row['shippingrecord_tracking'] .'</td></tr>';
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
</body>
<script>
	<?php if ($block_editing){ ?>
	$('.editing').hide();
	$('.static').show();
	<?php } ?>
	
	$('.fa-print').on("click", function(){
		window.print();
	});
</script>
</html>