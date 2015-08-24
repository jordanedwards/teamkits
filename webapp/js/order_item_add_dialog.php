<script>

$(function() {
	$( "#order_item_add_dialog" ).dialog({
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
					if ($('#item_id').val() != null && $('#item_quantity').val() != 0){
					var newItem = $('#newComponentForm').serialize();
					$.ajax({
						url: "ajax/ajax_order_item.php?"+newItem,	
						dataType: "json",
						success: function (data) {	
							//console.log(data);
							if(data.success){
								//$('#order_items_table').append(html);
								location.assign("orders_edit.php?id="+$('#order_id').val());							
							}else{
								location.assign("orders_edit.php?id="+$('#order_id').val());							
							}		
						}	
					});
					
               		$(this).dialog('close');
					} else {
						alert("Please fill in all fields");
					}
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
	$(".add-item").on("click", function (e) {
		e.preventDefault();
		$('#order_item_add_dialog').dialog('open');
	});
	
});

	function updateSizeList(){
		var selectedItem = $('#item_id').val();
	
		if (selectedItem == 17){
			//Shipping charges:
			$('#item_price').show();
			$('#size_div').hide();
			$('#item_quantity').val(1);
			$('#item_quantity').hide();
		} else {
			$.ajax({
				url: "ajax/ajax_get_sizes.php?item_id="+selectedItem,	
				success: function (html) {	
					$('#size_div').html(html);
				}
			});
			$('#item_quantity').show();	
			$('#item_price').hide();					
			$('#size_div').show();
		}
	}
	
</script>


<div id="order_item_add_dialog" title="Add Order Item" style="display:none">
<form id="newComponentForm" method="post">
 <p>
 <?php
	$dd = New DropDown();
	$dd->set_table("item");
	$dd->set_name_field("item_name");
	$dd->set_name("item_id");
	$dd->set_active_only(true);
	$dd->set_class_name("form-control inline");
	$dd->set_order("ASC");
	$dd->set_placeholder("Select item");	
	$dd->set_onchange('updateSizeList();');
	$dd->set_required(true);
	$dd->display();
	?>	  	  
  </p>
 <p>
<input type="number" min="1" step="1" placeholder="Quantity" style="width: 90%;" class="form-control inline" name="item_quantity" id="item_quantity" required/> 
</p>
<p  id="item_price" style="display:none">
<input type="number" min="0" step="0.01" placeholder="Price" style="width: 90%;" class="form-control inline" name="item_price" /> 
<br />
<input type="checkbox" name="notify_customer" checked="checked" value="true"/>&nbsp;Email customer?
</p>
<p style="display:none" id="size_div">

</p>
<input type="hidden" name="action" value="add"/>
<input type="hidden" id="order_id" name="order_id" value="<?php echo $order->get_id() ?>"/>
</form>
</div>	