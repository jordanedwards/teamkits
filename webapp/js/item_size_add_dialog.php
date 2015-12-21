<script>

$(function() {
	$( "#add_size_dialog" ).dialog({
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
					var newItem = $('#newSizeForm').serialize();
						$.ajax({
						url: "ajax/ajax_item_size.php?"+newItem,	
						success: function (html) {	
							$('#sizes_table').append(html);
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
	$(".add-size").on("click", function (e) {
		e.preventDefault();
		$('#add_size_dialog').dialog('open');
	});
	
	$( "#payment_date" ).datepicker({
		dateFormat: "yy-mm-dd",
		changeMonth: true,
		changeYear: true
	});
});
</script>


<div id="add_size_dialog" title="Add Size" style="display:none">
<form id="newSizeForm" method="post">
<h4>Please select:</h4>
 <p>
 <?php
	$dd = New DropDown();
	$dd->set_table("sizes");
	$dd->set_name_field("sizes_name");
	$dd->set_index_name("sizes_name");
	$dd->set_name("size");
	$dd->set_class_name("form-control inline");
	$dd->set_active_only(true);
	$dd->set_required(true);	
	$dd->set_placeholder("Select size");
	$dd->display();	
	?>	  	  
  </p>
 <p>
<input type="number" placeholder="Stock - leave empty for unlimited" style="width: 90%;" step="0.01" min="0" class="form-control inline" name="stock"/>
</p>

 <p>
  	  
  </p>
<input type="hidden" name="item_id" value="<?php echo  $item_id ?>"/>
<input type="hidden" name="action" value="add"/> 
</form>
</div>	