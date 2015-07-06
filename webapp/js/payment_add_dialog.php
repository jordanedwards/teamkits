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