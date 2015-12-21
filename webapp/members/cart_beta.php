<?php 
$public=true;
require("../classes/class_cart.php");
require("../includes/init.php"); 
//require(INCLUDES . "acl_module.php"); 
$activeMenuItem = "Cart";

require_once(CLASSES . "class_user.php"); 	
require_once(CLASSES . "/class_club.php"); 
$club = new Club();	 
$club->get_by_id($session->get_club()); 

if (!$club->get_id() >0){
		$session->setAlertColor("red");			
		$session->setAlertMessage("Please login");
		header("location: /login.php");		
}

if (!isset($_SESSION['cart'])){
	$_SESSION['cart'] = new cart();
} 
	$cart = $_SESSION['cart'];
	
if (isset($_POST['reset_cart'])){
	$cart->reset_cart();
}

if (isset($_POST['stripeToken'])){
//Payment submitted, create order.

	require("../stripe/stripe-php-2.3.0/init.php");
	require_once(CLASSES . "/class_customer.php"); 		
	require_once(CLASSES . "/class_orders.php"); 	
	require_once(CLASSES . "/class_orderitem.php"); 		
	require_once(CLASSES . "/class_payment.php"); 

	\Stripe\Stripe::setApiKey(STRIPE_API_KEY);

	// Get the credit card details submitted by the form
	$token = $_POST['stripeToken'];

	// Create the charge on Stripe's servers - this will charge the user's card
	try {
		$charge = \Stripe\Charge::create(array(
		  "amount" => $_POST['payment_amount'], // amount in cents, again
		  "currency" => "usd",
		  "source" => $token,
		  "description" => "Teamkits order"));
		} catch(\Stripe\Error\Card $e) {
		  // The card has been declined
			$session->setAlertMessage("Credit card payment failed. Please try again.");
			$session->setAlertColor("red");
		}	
		$amount = $_POST['payment_amount']/100;
		
		// Create new customer & order";
		$customer = new Customer();
		$customer->load_from_post($_POST);
		$customer->set_club_id($club->get_id());		
		$customer->save();
		
		// Should check to see if there is anything on the order besides a jersey. If not, then no need to create an order:
		$order = new Orders();
		$order->set_type("customer");
		$order->set_status(4);
		$order->set_customer_id($customer->get_id());
		$order->set_club_id($club->get_id());
		$order->save();

		//Get items
		$item_array = $cart->get_items();
		$orderitem = new Orderitem();
		$jersey_payments = 0;
		
		foreach($item_array as $name => $val){
			if ($val['jerseyRecord_id'] > 0){
				// Order item is actually an item on a parent club order. Need to just flag this as paid for the parent order:
				require_once(CLASSES . "/class_jerseyRecord.php");
				$jersey_record = new JerseyRecord();
				$jersey_record->get_by_id($val['jerseyRecord_id']);
				if ($jersey_record->get_id() > 0){
					$jersey_record->set_status("paid");
					$jersey_record->save();
					
					// Add payment to parent order
					$new_payment = new Payment();
						$orderitem->get_by_id($jersey_record->get_orderitem_id());
					$new_payment->set_order_id($orderitem->get_order_id());
					$new_payment->set_transaction_number($token);
					$new_payment->set_amount($orderitem->get_price());
					$new_payment->set_method('3');
					$new_payment->set_status('Completed');
					$new_payment->set_active('Y');
					$new_payment->save();	
					
					// Add up the portion of this payment that will be going to the jerseys:
					$jersey_payments = $jersey_payments +$orderitem->get_price();
					// Need to add in here an order method that checks if order is fully paid
				}
			} else {
				//Item is ordered just by the customer:
				$orderitem->clear();
				$orderitem->set_order_id($order->get_id());
				$orderitem->set_item_number($val['item_id']);
				$orderitem->set_quantity($val['qty']);
				$orderitem->set_size($val['size']);
				$orderitem->set_price($val['price']);
				$orderitem->save();
			}
		}
		
		// Add shipping as order item:
		$orderitem->clear();
		$orderitem->set_order_id($order->get_id());
		$orderitem->set_item_number(17);
		$orderitem->set_quantity(1);
		$orderitem->set_price($_POST['shippingcharges_hidden']);
		$orderitem->save();
		
		//Payment processed, add record
		$new_payment = new Payment();
		$new_payment->set_order_id($order->get_id());
		$new_payment->set_transaction_number($token);
		$new_payment->set_amount($amount-$jersey_payments);
		$new_payment->set_method('3');
		$new_payment->set_status('Completed');
		$new_payment->set_active('Y');
		$new_payment->save();

		$order->refresh();
		$order->recalculate();
		$order->save();
						
		if ($_POST['email'] != ""){
			//Initiate the emailer
			require_once(INCLUDES . 'config_mail.php');
			require_once(CLASSES . 'class_phpmailer.php');
				
			$mail = new PHPMailer();
			$mail->IsHTML(true);
			$mail->From = $mailConfig["mail_from"];
			$mail->FromName = $mailConfig["mail_fromname"];
			$mail->Sender = $mailConfig["mail_sender"];
			$mail->AddAddress("jordan@orchardcity.ca", "");
			$mail->AddAddress("info@teamkits.net", "");			
			// Need to regex email:
			$mail->AddAddress($_POST['email'], "");		
			$mail->WordWrap = 50; // set word wrap to 50 characters
			$mail->Subject = "Teamkits Order #" . $order->get_id();
			$body = "These are the details of your order:<br>
			" . $cart->show_items();
			$mail->Body = $body;
			
			$mail->Send();
		}
		
		// Email admins
		if ($_POST['email'] != ""){
			//Initiate the emailer
			require_once(INCLUDES . 'config_mail.php');
			require_once(CLASSES . 'class_phpmailer.php');
				
			$mail = new PHPMailer();
			$mail->IsHTML(true);
			$mail->From = $mailConfig["mail_from"];
			$mail->FromName = $mailConfig["mail_fromname"];
			$mail->Sender = $mailConfig["mail_sender"];
			$mail->AddAddress("jordan@orchardcity.ca", "");
			$mail->AddAddress("info@teamkits.net", "");			
			$mail->WordWrap = 50; // set word wrap to 50 characters
			$mail->Subject = "Order# " . $order->get_id() . " placed";
			$body = "The following club member order has been placed:<br>
			" . $cart->show_items() . "<br>
			<a href='https://teamkits.net/webapp/orders_edit.php?id=1'><em>Go to order</em></a>
			";
			$mail->Body = $body;
			
			$mail->Send();
		}		
										
		$session->setAlertMessage("Order #" . $order->get_id() ." submitted, thank you.");
		$session->setAlertColor("green");
		
		$cart->reset_cart();
}
 ?>
 <!DOCTYPE html>
<html lang="en">
<head>
   <?php  include(HEAD);  ?>
    <title>Shopping Cart | <?php echo $appConfig["app_title"]; ?></title>
<link href='https://fonts.googleapis.com/css?family=Josefin+Sans' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
<!--<link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css" rel="stylesheet" />-->
<style>
.widget .widget-header.promo-header {
	height:auto;
}
.promo-header h2{
  padding: 10px 0 5px 10px;
    font-size: 1.7em;
 }
 .form-control.inline {
    margin-bottom: 5px;
}
</style>
</head>
<body>
<?php require(INCLUDES . "navbar.php");?>

<div class="main">
    <div class="container">
		<div class="row">
			<div class="row">
			<div class="col-md-12">	  
				  <?php  include(INCLUDES . "system_messaging.php");  ?>
			</div>
		</div>
			
			<div class="col-md-8">
			<h3>Shopping cart items:</h3>
			<pre>
				<?php
					//echo $cart->show_items();
					print_r($cart->get_items());
					
				?>
				</pre>

				<table class="admin_table">
					<thead>
					<tr><th>Item:</th><th>Quantity</th><th>Size</th><th>Price</th><th style="width: 50px;">Remove</th></tr>		
					</thead>
					
					<tbody id="order_items_table">
					<?php
						foreach ($cart->get_items() as $key=>$val){
						
						if ($val['jerseyRecord_id'] == 0){
						// Qty editable, as this is part of a bulk order and shipping has already been calulated
						?>
							<tr style='background: #efefef;'><td><?php echo ucfirst($key) ?></td><td>
								<input type="number" class="change_qty" data-key="<?php echo $key ?>" value="<?php echo $val["qty"] ?>" min="1" step="1" style="width:60px">							
							</td><td>
							<?php 
							$dd = new DropDown();
							$dd->set_table("itemSize");
							$dd->set_name_field("itemSize_name");
							$dd->set_selected_value($val["size"]);
							$dd->set_index_name("itemSize_name");
							$dd->set_class_name("change_size");
							$dd->set_active_only(true);
							$dd->set_order("ASC");
							$dd->set_where("itemSize_item_id = ". $val['item_id']);	
							$dd->add_data("key",$key);
							$dd->display();							
							?>
							</td><td style='text-align: right;'>$<?php echo number_format($val['price'], 2, '.', ',') ?></td><td style='text-align: center;'><a href='#' data-name='<?php echo $key ?>' class='remove-item'><i class='fa fa-times-circle fa-lg'></i></a></td></tr>
						<?
						} else {
						?>
							<tr style='background: #efefef;'><td><?php echo ucfirst($key) ?></td><td><?php echo $val["qty"] ?></td>
							<td><?php 
							$dd = new DropDown();
							$dd->set_table("itemSize");
							$dd->set_name_field("itemSize_name");
							$dd->set_selected_value($val["size"]);
							$dd->set_index_name("itemSize_name");
							$dd->set_class_name("change_size");
							$dd->set_active_only(true);
							$dd->set_order("ASC");
							$dd->set_where("orderitem_id = ". $val['orderitem_id']);	
							$dd->add_data("key",$key);
							$dd->add_join("orderitem","itemSize_item_id","orderitem_item_number");
							$dd->display();							
							?></td>
							<td style='text-align: right;'>$<?php echo number_format($val['price'], 2, '.', ',') ?></td><td style='text-align: center;'><a href='#' data-name='<?php echo $key ?>' class='remove-item'><i class='fa fa-times-circle fa-lg'></i></a></td></tr>						
						<?
						}
							$itemCount = $itemCount + $val["qty"];
						}	
					?>					
					</tbody>
					<tfoot>
						<tr style='background: #efefef;'><td>Subtotal:</td><td id="itemCount"><?php echo $itemCount ?></td><td></td><td style='text-align: right;'>$<?php echo $cart->cart_total() ?></td><td></td></tr>
					</tfoot>
				</table>
						
				<br>
				<table class="admin_table">
					<tr><td>Shipping charges (Xpresspost):</td><td id="shippingcharges"  style='text-align: right;'><i>Enter country and postal/zip code to calculate</i></td></tr>
					<tr><td>Tax:</td><td id="taxcharges"  style='text-align: right;'><i>Enter country and postal/zip code to calculate</i></td></tr>					
					<tr><td>Total:</td><td id="total"  style='text-align: right;'><i>Enter country and postal/zip code to calculate</i></td></tr>
				</table>
				<br>
		<form action="" method="POST" style="display: inline-block;">
			<input class="btn btn-default" name="reset_cart" value="Reset Cart" type="submit">
		</form>
			</div>
			<div class="col-md-4">	
				<h4 style="margin-top:10px;">Please enter your shipping information:</h4>
				<form action="" method="POST" style="display: inline-block;">
					<input name="name" class="form-control inline addressField" placeholder="Customer name" required value="<?php echo $session->getData("name"); ?>"><br>
					<input name="address" class="form-control inline addressField" placeholder="Address" required value="<?php echo $session->getData("address"); ?>"><br>
					<input name="city" class="form-control inline addressField" placeholder="City" required value="<?php echo $session->getData("city"); ?>"><br>
					<input name="prov" class="form-control inline addressField" placeholder="Province/State" required value="<?php echo $session->getData("prov"); ?>"><br>
					<?php 
						$dd = New DropDown();
						$dd->set_table("country");
						$dd->set_name_field("country_name");
						$dd->set_name("country");
							$country = ($session->getData("country")!=NULL ? $session->getData("country") : $club->get_country());
						$dd->set_selected_value($country);
						$dd->set_class_name("form-control inline addressField");
						$dd->set_active_only(true);
						$dd->set_required(true);	
						$dd->set_placeholder("Country");
						$dd->display();
					?>
					<input name="postal" id="postal" class="form-control inline addressField" placeholder="Postal/Zip Code" required value="<?php echo $session->getData("postal"); ?>"><br>
					<input name="phone" class="form-control inline addressField" placeholder="Phone" required value="<?php echo $session->getData("phone"); ?>"><br>
					<input name="email" class="form-control inline addressField" placeholder="Email *" value="<?php echo $session->getData("email"); ?>"><p class="small">* include if you would like an emailed receipt</p>

			<input type="hidden" name="taxcharges_hidden" value="" id="taxcharges_hidden">					
			<input type="hidden" name="shippingcharges_hidden" value="" id="shippingcharges_hidden">					
			<input type="hidden" name="payment_amount" id="payment_amount" value="<?php echo $cart->cart_total()*100 ?>">
			<div id="stripe_btn">
			  <script
				src="https://checkout.stripe.com/checkout.js" class="stripe-button"
				data-key="<?php echo STRIPE_API_KEY_PUBLIC ?>"
				data-amount="<?php echo $cart->cart_total()*100 ?>"
				data-name="Order"
				data-description="Shopping cart"
				data-image="/images/128x128.png">
			  </script>
			 </div>
			</form>
						
			</div>	
		</div>
	</div>
</div> 
    
<?php require(INCLUDES . "footer.php"); ?>
<?php require(INCLUDES_LIST);?>
<!--<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>-->

<?php 
	require_once(CLASSES."class_tax.php");
	$tax = new Tax();
	$tax->get_by_id($club->get_tax_id());
	if ($tax->get_tax_percentage() >0){
		$tax_percentage = $tax->get_tax_percentage();
	} else	{
		$tax_percentage = 0.00;	
	}
?>
				
</body>
<script>
	$(".remove-item").on("click", function (e) {
		e.preventDefault();
		var itemName = $(this).data("name");
		$.ajax({
		  	type: "POST",
		  	url: "ajax_cart.php",
		  	data: { name: itemName, action:'delete'},
		  	success: function (html) {
				//$('#alert_message').html(html);
				//$('#alert_message').removeClass();				
				//$('#alert_message').addClass(data.alertColor);				
				//$('#alert_message').show();
				location.reload();
			}
		});
	});	
	
	$("#postal").on("blur", function (e) {
		e.preventDefault();
	
		$(this).val($(this).val().toUpperCase());
		var postalcode = $(this).val().replace(" ", "");
		
		if(postalcode.length == 6){
		var countryVar = $('#country').val();
		$.ajax({
		  	type: "POST",
		  	url: "../ajax/ajax_cpc_item.php",
			dataType: "json",			
		  	data: {pc_origin:"<?php echo ORIGIN_POSTAL_CODE ?>", pc_destination:postalcode, weight:'<?php echo $cart->get_weight() ?>', country:countryVar},
		  	success: function (data) {
				// Successful postal code lookup
				if(data['Xpresspost'] >0){
					// Canada
					var shippingValue = data['Xpresspost'];
				}
				if(data['Xpresspost USA'] >0){
					//US
					var shippingValue = data['Xpresspost USA']; 
				}
				if(data['zeroval'] == "true"){
					var shippingValue =	0;
				}
				
				$('#shippingcharges').html("$"+shippingValue);

				$('#shippingcharges_hidden').val(shippingValue);
				var newTotal = <?php echo $cart->cart_total()*100 ?>+shippingValue*100;
								
				var taxamount = <?php echo $tax_percentage ?>*newTotal;
				var newTotal = (newTotal + taxamount).toFixed(0);

				var taxamount = (taxamount/100).toFixed(2);
				$('#taxcharges').html("$"+taxamount);
				$('#taxcharges_hidden').val(taxamount);	

				$('#payment_amount').val(newTotal);
				var newTotal = (newTotal/100).toFixed(2);
				$('#total').html("$"+newTotal);
				
				// reload button with new total
				$('#stripe_btn').html("");				
				$('<script src="https://checkout.stripe.com/checkout.js" class="stripe-button" data-key="<?php echo STRIPE_API_KEY_PUBLIC ?>" data-amount="'+newTotal*100+'" data-name="Order" data-description="Shopping cart" data-image="/'+'images/'+'128x128.png"></'+'script>').appendTo('#stripe_btn');				

			},
			error: function (data){
				console.log(data);
				$('#shippingcharges').html("Unable to calculate from address given");
				$('#stripe_btn').html("");
			}
		});
		}
	});		

$('.stripe-button-el').attr("disabled",true);	
</script>

<script>
	$(".change_size").on("blur", function (e) {
		e.preventDefault();
		var itemName = $(this).data("key");
		var newSize = $(this).val();
		
		$.ajax({
		  	type: "POST",
		  	url: "ajax_cart_item.php",
		  	data: { name: itemName, size: newSize, action:'update_size'},
		  	success: function (html) {
			}
		});
	});	
	
	$(".change_qty").on("blur", function (e) {
		e.preventDefault();
		var itemName = $(this).data("key");
		var newQty = $(this).val();
		
		$.ajax({
		  	type: "POST",
		  	url: "ajax_cart_item.php",
		  	data: { name: itemName, qty: newQty, action:'update_qty'},
		  	success: function (html) {
				location.reload();
			}
		});
	});		
</script>
<script>
	$(".addressField").on("blur", function (e) {
		var name = $(this).attr("name");
		var val = $(this).val();
		write_sess_var(name,val);
	});	
	
$( document ).ready(function() {	
	$( "#postal" ).trigger( "blur" );	
});
</script>

</html>