<?php 
require("includes/init.php"); 
$page_id = basename(__FILE__);
$activeMenuItem = "Payment";				
require(INCLUDES . "/acl_module.php");
require_once(CLASSES . "/class_payment.php"); 
 
if(!isset($_GET["id"])) {
	$session->setAlertMessage("Can not edit - the ID is invalid. Please try again.");
	$session->setAlertColor("yellow");
	header("location:" . BASE_URL . "/index.php");
	exit;
}

// load the payment		
$payment_id = $_GET["id"];
$payment = new Payment();

if ($_GET["id"] ==0){
	// Change this to pass a parent value if creating a new record:
	//	$payment->set_customer_id($_GET['customer_id']);
} else {
	$payment->get_by_id($payment_id);
}
 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
	<?php  include(HEAD);  ?>
    <meta name="description" content="">

    <title><?php   echo $appConfig["app_title"];  ?> | Payment Edit</title>
  </head>

  <body>

<?php  require(INCLUDES . "navbar.php");  ?>
<div class="main">

    <div class="container">
      <div class="row">
        <div class="col-md-12">
        <?php  include(INCLUDES . "system_messaging.php");  ?>

        <h1><?php if ($_GET["id"] ==0){ ?> Add Payment<?php  } else { ?> Edit Payment<?php  } ?></h1>
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
	<form id="form_payment" action="<?php  echo ACTIONS_URL; ?>action_payment_edit.php" method="post">
	<input type="hidden" name="id" value="<?php  echo $payment->get_id();  ?>" />
	<input type="hidden" name="action" value="edit" />	
	<input type="hidden" name="page_id" value="<?php  echo $page_id  ?>" />	
	
         <table class="admin_table">
				<tr>
           			<td style="width:1px; white-space:nowrap;">Payment order id: </td>
            		<td><input id="payment_order_id" name="payment_order_id" class="form-control inline" type="number" step="any" value="<?php  echo $payment->get_payment_order_id();  ?>"  /></td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Payment transaction number: </td>
            		<td><input id="payment_transaction_number" name="payment_transaction_number" class="form-control inline" type="text"  value="<?php  echo $payment->get_payment_transaction_number();  ?>"  /></td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Payment amount: </td>
					<td>$<input id="payment_amount" name="payment_amount" class="form-control inline" type="number" step=".01" min="0" value="<?php  echo $payment->get_payment_amount();  ?>"   /></td>


				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Payment method: </td>
				
					<td>
					<?php 
						$dd = new DropDown();
						$dd->set_table("paymentmethod");	
						$dd->set_name_field("paymentmethod_title");
						$dd->set_class_name("form-control inline");
						$dd->set_order("ASC");						
						$dd->set_name("payment_method");						
						$dd->set_selected_value($payment->get_payment_method());
						$dd->display();
					 ?>											
					</td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Payment status: </td>

					<td>
					<?php 					
						$dd = new DropDown();
						$dd->set_static(true);	
						$dd->set_name("payment_status");
						$dd->set_class_name("form-control inline");
						$dd->set_option_list("Y,N");						
						$dd->set_selected_value($payment->get_payment_status());
	
						$dd->display();
					 ?>											
					</td>
				</tr>
  		
		</table>
          <br />
          <input type="submit" class="btn-primary" value="<?php if ($payment_id ==0){ ?> Add <?php  } else { ?> Save <?php  } ?>" />
          <?php  if ($payment_id > 0){  ?>
		  <a href="<?php  echo ACTIONS_URL  ?>action_kit_edit.php?action=delete&page_id=<?php  echo $page_id  ?>&id=<?php  echo $payment_id  ?>" onClick="return confirm('You are about to delete this item. Do you want to continue?');" class="btn btn-warning" role="button">Delete</a>
		   <?php  }  ?> 		  
          <input type="button" class="btn-default" value="Back" onClick="window.location ='<?php echo $_SERVER["HTTP_REFERER"];?>'" />
        </form>
		<br>
		
        <?php  if($payment->get_id() > 0){  ?>
          <p><em>Last updated: <?php  echo $payment->get_last_updated();  ?> by <?php  echo $payment->get_last_updated_user();  ?></em></p>
        <?php  }  ?>			
	
      </div>
    </div> 

</div><!-- /container -->
</div>

<?php  include(INCLUDES . "/footer.php");  ?>
<?php  include(INCLUDES_LIST);  ?>	
<script>
// Include any masks here:
		 //   $("#student_tel").mask("(999) 999-9999");

$(document).ready(function() {
	$("#payment_amount").mask("999999999999.99"); 
});	

</script>
  </body>
</html>