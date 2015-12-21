<?php 
// Still need to check status (only allow viewing of orders that are waiting for payment +)
$public = true;
require("../includes/init.php"); 
$page_id = "club_admin/" . basename(__FILE__);

require_once(CLASSES . "class_user.php"); 	
require_once(CLASSES . "class_club.php");
$club = new Club();	 
$club->get_by_id($session->get_club()); 

if (!$club->get_id() >0){
	$session->setAlertColor("red");			
	$session->setAlertMessage("Please login");
	header("location: /login.php");		
}

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
		$order->set_club_id(0);
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

	/*if($session_id() != $order->get_sess_id()) {
	// Block user from viewing other user's orders:
		$session->setAlertMessage("Access denied: This order belongs to a different user");
		$session->setAlertColor("red");
		header("location:" . BASE_URL . "/index.php");
		exit;
	}*/
	
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

	$session->setAlertMessage("Club members may pay for their own items on this order individually. Once the order deadline arrives, we'll automatically submit the order. If any items do not have a payment, they will not be included in the order. If complete payment is made before the deadline, we'll submit the order right away.");
	$session->setAlertColor("blue");

 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
  <?php  include(HEAD);  ?>

    <title><?php   echo $appConfig["app_title"];  ?> | View Order </title>
	<!--<link href="../css/carousel.css" rel="stylesheet">    -->
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
  /*background: #fff;*/
 /* padding: 5px;*/
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
.ui-accordion .ui-accordion-content {
    padding: 0;
}
.ui-state-active {
    background: #DDAB30 !important;
}
.admin_table.smaller td {
	font-size:0.9em;
}
.ui-accordion-content-active {
	margin-bottom:10px;
}
@media (min-width:992px){
#preview_window{ 
	position:fixed;
	width:400px;
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

        <h1>View Order #<?php echo $order->get_id()?> <i class="fa fa-print fa-right no_print"></i></h1>

			<p>Click on the player name, then shopping cart icon next to the items to add to your cart.</p>

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
			<p class="static"><?php echo $order->get_deadline(); ?></p>
		</td>
	</tr>  	
	<?php } ?>
</table>
<br>
<div id="accordion">			
		 <?php 
		 	$dm = new DataManager(); 
			$quantity_count_sql = "SELECT SUM(orderitem_quantity) AS qtycount from orderitem 
			LEFT JOIN item ON orderitem.orderitem_item_number = item.item_id 
			WHERE orderitem.orderitem_order_id=" . $order->get_id() . "
			AND orderitem.is_active = 'Y'";
			$result = $dm->queryRecords($quantity_count_sql);	
			if ($result):
				while($row = mysqli_fetch_assoc($result)):
					$quantity_count = $row['qtycount'];
				endwhile;
			endif;
			
			$shipping_charge_sql = "SELECT orderitem_price from orderitem 
			LEFT JOIN item ON orderitem.orderitem_item_number = item.item_id 
			WHERE item_id=17 AND orderitem.orderitem_order_id=" . $order->get_id() . "
			AND orderitem.is_active = 'Y'";			
			$result = $dm->queryRecords($shipping_charge_sql);	
			if ($result):
				while($row = mysqli_fetch_assoc($result)):
					$shipping_charges = $row['orderitem_price'];
				endwhile;
			endif;	
							
			$charge_per_item = $shipping_charges/($quantity_count-1);
			
			$strSQL = "SELECT *, jerseyRecord.id AS jerseyRecordId from orderitem 
			LEFT JOIN item ON orderitem.orderitem_item_number = item.item_id 
			LEFT JOIN jerseyRecord ON orderitem.orderitem_id = jerseyRecord.orderitem_id
			WHERE orderitem.orderitem_order_id=" . $order->get_id() . "
			AND orderitem.is_active = 'Y' AND jerseyRecord.is_active = 'Y'
			ORDER BY name ASC, orderitem.orderitem_item_number
			";						

			$result = $dm->queryRecords($strSQL);	
			if ($result):
				while($row = mysqli_fetch_assoc($result)):
					if($row['item_id']==17){
					// Don't show shipping charge
					} 
					
					else {
						// Create row:
						if ($row['status'] == "paid"):
							$line_val =  '<tr><td>Paid</td>';		
						else:
							$line_val = '<tr><td><a class="add-item no_print" data-orderitem-id="' . $row['orderitem_id'] .'" data-jerseyrecord-id="' .$row['jerseyRecordId']. '"><i class="fa fa-cart-plus fa-lg"></i></a></td>';		
						endif;	
						$line_val .= '<td><a class="imagePreview" data-item-id="' . $row['orderitem_item_number'] . '">' . $row['item_name'] . '</a></td><td>#' . $row['number'] . '</td><td>' . $row['orderitem_size'] . '</td><td style="white-space:normal; text-align:right;">$'.sprintf("%.2f",$row['orderitem_price']+$charge_per_item) .'</td></tr>
						';					
					
						if (isset($row['number'])){
							$number_text = " #" . $row['number'];
						}else {
							$number_text = "";
						}					
						
						if (!isset($rowname)){

							echo '
							<div>
							<h3>' . $row['name'] . $number_text .'</h3>
							<div>
							<table class="admin_table smaller">';
							
							echo $line_val;
									
						}elseif ($row['name'] != $rowname){
							echo '
								</table>
							  </div>
							  </div>
							  
							  <div>
								<h3>' . $row['name'] . $number_text .'</h3>
							  	<div><table class="admin_table smaller">
							  ';
								echo $line_val;
						} else {
								echo $line_val;
						}
					
						$rowname = $row['name'];
					}

				endwhile;
				echo "</table></div></div>
				";
			endif;
		 ?>	
		 	</div>
		<br>
		<?php // echo $strSQL ?>
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
	
          <input type="button" class="btn btn-default" value="Go to Cart" onClick="window.location ='cart.php'" />
		<br>			
	
      </div>
	<div class="col-md-4">
			<div id="preview_window" class="no_print">
			
			</div>
			
			<br>
	</div>
    </div> 

</div><!-- /container -->
</div>

<?php  include(INCLUDES . "/footer.php");  ?> 
<?php require(INCLUDES_LIST);?>			
<?php include("order_item_add_dialog.php"); ?>  
<script>

$(function() {
	
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
$(function() {
	// promom item clicked:
	$(".add-item").on("click", function (e) {
		e.preventDefault();
		$(this).html('<i class="fa fa-cart-arrow-down fa-lg" style="color: #666;">');
		var orderItemId = $(this).data("orderitem-id");
		var jerseyRecordId = $(this).data("jerseyrecord-id");
		var itemSize = "";
	//	var itemQuantity = $('#promoQuantity_'+promoId).val();
	//	var itemSize = $('#promoSize_'+promoId).val();
	//	console.log("item: "+itemId+" quantity: "+itemQuantity+" size: "+itemSize);
		$.ajax({
		  	type: "POST",
		  	url: "ajax_cart.php",
			//dataType: "json",
		  	data: { order_item_id: orderItemId, action:'add', jersey_record_id:jerseyRecordId, order_item_id:orderItemId},
		  	success: function (html) {
				$('#alert_message').html(html);
				$('#alert_message').removeClass();				
				//$('#alert_message').addClass(data.alertColor);				
				$('#alert_message').show();
				//console.log(data);
			}
		});
	});
	
});
</script>
<script>
 /* $(function() {
    $( "#accordion" ).accordion();
  });*/
 /* $(function() {
    $("#accordion").accordion({autoHeight: false, heightStyle: "content", collapsible: true});    
});*/
 $(document).ready(function() {
     $("#accordion").accordion({ header: "h3", autoHeight: false, heightStyle: "content", navigation: true });
  });
  </script>
</html>