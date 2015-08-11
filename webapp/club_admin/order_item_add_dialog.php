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
					var newItem = $('#newComponentForm').serialize();
						$.ajax({
						url: "ajax_order_item.php?"+newItem,	
						success: function (html) {	
							//$('#order_items_table').append(html);
							location.assign("orders_edit.php?id="+$('#order_id').val());
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
	$(".add-item").on("click", function (e) {
		e.preventDefault();
		$('#order_item_add_dialog').dialog('open');
	});
	
});

	function updateSizeList(){
		var selectedItem = $('#item_id').val();
		$.ajax({
			url: "../ajax/ajax_get_sizes.php?item_id="+selectedItem,	
			success: function (html) {	
				$('#size_div').html(html);
			}
		});
		$('#size_div').show();
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
	$dd->set_where(' item_club_id ='.$club->get_id().' OR item_club_id=0 AND item_brand =' . $club->get_brand() . ' OR item_club_id=0 AND item_brand =0');
	$dd->display();
	?>	  	  
  </p>
 <p>
<input type="number" placeholder="Quantity" style="width: 90%;" class="form-control inline" name="item_quantity"/> 
</p>
<p style="display:none" id="size_div">

</p>
<input type="hidden" name="order_id" id="order_id"  value="<?php echo  $order->get_id() ?>"/>
<input type="hidden" name="action" value="add"/>
  </p>  
</form>
</div>	