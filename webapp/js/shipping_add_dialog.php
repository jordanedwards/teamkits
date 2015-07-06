<script>

$(function() {
	$( "#shipping_add_dialog" ).dialog({
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
					var newItem = $('#newShippingForm').serialize();
					//console.log(newItem);
						$.ajax({
						url: "ajax/ajax_shipping_item.php?"+newItem,	
						success: function (html) {	
							$('#shippingrecord_table').append(html);
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
	$(".add-shipping").on("click", function (e) {
		e.preventDefault();
		$('#shipping_add_dialog').dialog('open');
	});
	
	$( "#shippingrecord_date" ).datepicker({
		dateFormat: "yy-mm-dd",
		changeMonth: true,
		changeYear: true
	});
});
</script>


<div id="shipping_add_dialog" title="Add Shipping Record" style="display:none">
<form id="newShippingForm" method="post">
<h4>Please select:</h4>
 <p>
 <?php
 	$dd = New DropDown();
	$dd->set_static(true);	
	$dd->set_name("shippingrecord_carrier");	
	$dd->set_required(true);
	$dd->set_class_name("form-control");
		$option_list = "Canada Post,Fed Ex,UPS,Other";
	$dd->set_option_list($option_list);	
	$dd->set_placeholder("Select carrier");		
	$dd->display();	
	?>	  	  
  </p>
 <p>
<input type="text" placeholder="Tracking Number" style="width: 90%;" class="form-control inline" name="shippingrecord_tracking"/>
</p>
<p>
<input type="text" name="shippingrecord_date" id="shippingrecord_date" class="form-control"  placeholder="Shipping date" required/>
<input type="hidden" name="order_id" value="<?php echo  $orders_id ?>"/>
<input type="hidden" name="action" value="add"/>
  </p>  
</form>
</div>	