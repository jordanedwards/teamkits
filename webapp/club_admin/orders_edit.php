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
<<<<<<< HEAD
		$order->set_type("club");
=======
>>>>>>> f757d9f435864e736cc3bdfe6a140d905e3687d4
		$order->set_status(1);
		$order->save();	
		
		if (isset($_GET['additem'])){
			include(CLASSES . "/class_orderitem.php"); 
			foreach ($_GET['additem'] as $value) {
				$new_order_item = new Orderitem();
				$new_order_item->set_order_id($order->get_id());
				$new_order_item->set_item_number($value);
				$new_order_item->set_quantity(1);
				$new_order_item->get_item_details();
				$new_order_item->set_price($new_order_item->get_item_price());	
				$new_order_item->set_active("Y");
				$new_order_item->save();
			}
		}
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
		$session->setAlertMessage("Order created, kit added. Please edit the quantities desired and input player numbers/names by clicking on the edit icon next to the items.");
		$session->setAlertColor("green");		
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
		
		// If full payment has been made and status was "Waiting for payment", change to "Submitted";
		$order->set_status(4);
		$order->save();
					
		$session->setAlertMessage("Payment processed, thank you. Your order has been submitted");
		$session->setAlertColor("green");

		
	} catch(\Stripe\Error\Card $e) {
	  // The card has been declined
		$session->setAlertMessage("Credit card payment failed. Please try again.");
		$session->setAlertColor("red");
	}	
}
if(isset($_POST['submit'])){
	$order->submit();
}

if (isset($_POST['member_payment'])){
	$order->set_type("member");
	$order->save();
	$session->setAlertMessage("Club members will be able to pay for their own items on this order individually. Once the order deadline arrives, we'll automatically submit the order. If any items do not have a payment, they will not be included in the order. If complete payment is made before the deadline, we'll submit the order right away for you. <br>Please choose and expiry date below:");
	$session->setAlertColor("yellow");
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
/*  background: #fff;*/
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
label {
	display: inline-block;
	width: 5em;
}
<<<<<<< HEAD
<?php if ($order->get_status() != 1){
?>
.add-icon {
    color: #ccc;
}
<?php } ?>
=======
>>>>>>> f757d9f435864e736cc3bdfe6a140d905e3687d4
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
	<div class="col-md-8">
	
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
			<li <?php if ($order->get_status() >= 1 && $order->get_status() != 7){ echo " class='highlight' ";}?> style="padding: 2px;">Open</li>
			<li <?php if ($order->get_status() >= 2 && $order->get_status() != 7){ echo " class='highlight' ";}?> style="padding: 2px;">Waiting for shipping quote</li>			
			<li <?php if ($order->get_status() >= 3 && $order->get_status() != 7){ echo " class='highlight' ";}?> style="padding: 2px;">Waiting for payment</li>
			<li <?php if ($order->get_status() >= 4 && $order->get_status() != 7){ echo " class='highlight' ";}?> style="padding: 2px;">Submitted</li>			
			<li <?php if ($order->get_status() >= 5 && $order->get_status() != 7){ echo " class='highlight' ";}?> style="padding: 2px;">Shipped</li>
			<li <?php if ($order->get_status() >= 6 && $order->get_status() != 7){ echo " class='highlight' ";}?> style="padding: 2px;">Delivered</li>
		</ol>
			<?php if ($order->get_status() >= 7){ echo " <p class='highlight'>Cancelled</p>";}?>			
		</td>
	</tr>
	<tr>
		<td style="width:1px; white-space:nowrap;">Currency: </td>
		<td>
		<p class="static"><?php  echo $order->get_currency_shortname();  ?></p></td>
	</tr> 	
	<tr>
		<td style="width:1px; white-space:nowrap;">Description: </td>
		<td>
		<p class="static"><?php  echo $order->get_notes();  ?></p></td>
	</tr> 
	<?php if ($order->get_type() =="member"){?> 
	<tr>
		<td style="width:1px; white-space:nowrap;">Member Payment Deadline: </td>
		<td>
			<input type="text" id="deadline" name="deadline" value="<?php echo $order->get_deadline(); ?>">
		</td>
	</tr>  	
	<?php } ?>
</table>
<br>
<table class="admin_table">
			<thead>
<<<<<<< HEAD
			<tr><th colspan="6">Order Items: <p style="float:right; color:#<?php if ($order->get_status() == 1){ echo 'fff'; } else { echo 'ccc';} ?>" class="add-item">Add items</p><i class="fa fa-plus-circle fa-lg add-icon add-item no_print"></i></th></tr>
=======
			<tr><th colspan="6">Order Items:<i class="fa fa-plus-circle fa-lg add-icon add-item no_print"></i></th></tr>
>>>>>>> f757d9f435864e736cc3bdfe6a140d905e3687d4
			<tr><th ></th><th>Item:</th><th>#</th><th>Size</th><th>Price</th><th>Total</th></tr>		
			</thead>
			
			<tbody id="order_items_table">
		 <?php 
		 	$dm = new DataManager(); 
			$strSQL = "SELECT * from orderitem 
			LEFT JOIN item ON orderitem.orderitem_item_number = item.item_id 
			WHERE orderitem.orderitem_order_id=" . $order->get_id() . "
<<<<<<< HEAD
			AND orderitem.is_active = 'Y'
			ORDER BY item_name ASC, orderitem_size ASC";						
=======
			AND orderitem.is_active = 'Y'";						
>>>>>>> f757d9f435864e736cc3bdfe6a140d905e3687d4

			$result = $dm->queryRecords($strSQL);	
			if ($result):
				while($row = mysqli_fetch_assoc($result)):
<<<<<<< HEAD
				if ($order->get_status() == 1){
					echo '<tr><td><a href="orderitem_edit.php?id=' . $row['orderitem_id'] .'" class="no_print"><i class="fa fa-edit fa-lg"></i></a>&nbsp;&nbsp;<a href="actions/action_orderitem_edit.php?action=delete&page_id=orderitem_edit.php&id=' . $row['orderitem_id'] . '" onclick="return confirm(\'You are about to delete this item. Continue?\');" class="editing no_print"><i class="fa fa-times-circle fa-lg"></i></a></td>';
				} elseif ($order->get_status() == 2 && $row['item_name'] != "Shipping charges - USD" || $order->get_status() == 3 && $row['item_name'] != "Shipping charges - USD") {
				// allow editing of jersey numbers but not quantities or sizes if order status 2 or 3:
					echo '<tr><td><a href="jersey_number_edit.php?id=' . $row['orderitem_id'] .'" class="no_print">&raquo; Edit names/numbers</a></td>';				
				} else {
					echo '<tr><td></td>';
				}
					echo '<td><a class="imagePreview" data-item-id="' . $row['orderitem_item_number'] . '">' . $row['item_name'] . '</a></td><td>' . $row['orderitem_quantity'] . '</td><td>' . $row['orderitem_size'] . '</td><td style="white-space:normal; text-align:right;">$'.sprintf("%.2f",$row['orderitem_price']) .'</td><td style="white-space:normal; text-align:right ">$'.number_format(($row['orderitem_price']*$row['orderitem_quantity']),2) .'</td></tr>';
=======
					echo '<tr><td><a href="orderitem_edit.php?id=' . $row['orderitem_id'] .'" class="no_print"><i class="fa fa-edit fa-lg"></i></a>&nbsp;&nbsp;<a href="actions/action_orderitem_edit.php?action=delete&page_id=orderitem_edit.php&id=' . $row['orderitem_id'] . '" onclick="return confirm(\'You are about to delete this item. Continue?\');" class="editing no_print"><i class="fa fa-times-circle fa-lg"></i></a></td><td><a class="imagePreview" data-item-id="' . $row['orderitem_item_number'] . '">' . $row['item_name'] . '</a></td><td>' . $row['orderitem_quantity'] . '</td><td>' . $row['orderitem_size'] . '</td><td style="white-space:normal; text-align:right;">$'.sprintf("%.2f",$row['orderitem_price']) .'</td><td style="white-space:normal; text-align:right ">$'.number_format(($row['orderitem_price']*$row['orderitem_quantity']),2) .'</td></tr>';
>>>>>>> f757d9f435864e736cc3bdfe6a140d905e3687d4
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
		
		<?php if($order->get_status() == 3 && $total > 0){ ?>
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
	
	<form method="post" style="display:inline-block">
		<?php if($order->get_status() == 3 && $total > 0 && $order->get_type() !="member"){ ?>
			<input type="submit" class="btn btn-success" name="member_payment" value="Payment by club members" data-toggle="tooltip" title="Choose this option and the club members will be able to pay for their own items individually. Once the order deadline arrives, we'll automatically submit the order. If any items do not have a payment, they will not be included in the order."/>
		<?php } ?>		
		<?php if($order->get_status() == 1){ ?>
		 <input type="submit" class="btn btn-success" name="submit" value="Submit for Shipping Quote" data-toggle="tooltip" title="Once your order is ready to go, we'll get an accurate shipping price from the manufacturer and add it to your order. We'll let you know by email once the shipping price has been added." />
		   <a href="<?php  echo ACTIONS_URL  ?>action_orders_edit.php?action=delete&page_id=<?php  echo $page_id  ?>&id=<?php  echo $order->get_id()  ?>" onClick="return confirm('Cancel this order?');" class="btn btn-warning" role="button">Cancel Order</a>		
		  <?php } ?>
          <input type="button" class="btn btn-default" value="Back" onClick="window.location ='<?php echo $_SERVER["HTTP_REFERER"];?>'" />
		   </form>
		<br>			
	
      </div>
	<div class="col-md-4">
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
	$(document).ready(function(){
		$('[data-toggle="tooltip"]').tooltip(); 
	});
</script>
<script>
	$( "#deadline" ).datepicker({
		dateFormat: "yy-mm-dd",
		changeMonth: true,
		changeYear: true
	});
	
	$( "#deadline" ).on("change",function(){
		var id = '<?php echo $order->get_id();?>';
		var deadline = $("#deadline").val();
		
		$.ajax({
		  type: "POST",
		  url: "ajax_update_deadline.php",
		  data: { order_id: id, order_deadline:deadline},
		  success: function () {	
				$('#alert_message').removeClass();
				$('#alert_message').addClass("green");
				$('#alert_message').html("Payment deadline updated");
				$('#alert_message').show();				
			}
		});
	});
</script>
</html>