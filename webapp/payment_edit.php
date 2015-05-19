<?php 
require("includes/init.php"); 
$page_id = basename(__FILE__);
require(INCLUDES . "/acl_module.php");
include(CLASSES . "/class_payment.php"); 
 
	if(!isset($_GET["id"])) {
		$session->setAlertMessage("Can not edit - the ID is invalid. Please try again.");
		$session->setAlertColor("yellow");
		header("location:" . $base_href . "/index.php");
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
$activeMenuItem = "Payment";				
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
	<div class="col-md-8">
	<form id="form_payment" action="<?php  echo ACTIONS_URL; ?>action_payment_edit.php" method="post">
	<input type="hidden" name="payment_id" value="<?php  echo $payment->get_id();  ?>" />
	<input type="hidden" name="action" value="edit" />	
	<input type="hidden" name="page_id" value="<?php  echo $page_id  ?>" />	
	
         <table class="admin_table">
				<tr>
           			<td style="width:1px; white-space:nowrap;">Order id: </td>
            		<td><input id="payment_order_id" name="payment_order_id" type="text"  value="<?php  echo $payment->get_order_id();  ?>" style="width:90%" /> </td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Transaction number: </td>
            		<td><input id="payment_transaction_number" name="payment_transaction_number" type="text"  value="<?php  echo $payment->get_transaction_number();  ?>" style="width:90%" /> </td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Amount: </td>
					<td><input id="payment_amount" name="payment_amount" type="number" step=".01" value="<?php  echo $payment->get_amount();  ?>"  style="width:90%" /></td>

				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Method: </td>
            		<td><input id="payment_method" name="payment_method" type="number" step="any" value="<?php  echo $payment->get_method();  ?>" style="width:90%" /> </td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Status: </td>
            		<td><input id="payment_status" name="payment_status" type="text"  value="<?php  echo $payment->get_status();  ?>" style="width:90%" /> </td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Active: </td>
            		<td><input id="is_active" name="is_active" type="text"  value="<?php  echo $payment->get_active();  ?>" style="width:90%" /> </td>
				</tr>
  		
		</table>
          <br />
          <input type="submit" value="Add/Update Payment" />&nbsp;&nbsp;
          <input type="button" value="Cancel" onClick="window.location ='<?php echo $_SERVER["HTTP_REFERER"];?>'" />
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

<script type="text/javascript">
$(document).ready(function() {
	$("#payment_amount").mask('000000000000.00', {reverse: true}); 
});	
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