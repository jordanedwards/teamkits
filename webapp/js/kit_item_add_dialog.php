<script>

$(function() {
	$( "#kit_item_add_dialog" ).dialog({
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
						url: "ajax/ajax_kit_item.php?"+newItem,	
						success: function (html) {	
							$('#kit_items_table').append(html);
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
		$('#kit_item_add_dialog').dialog('open');
	});
	
});

	
</script>


<div id="kit_item_add_dialog" title="Add Kit Item" style="display:none">
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
	$dd->set_where("item_club_id = " .$kit->get_club_id() . " OR item_brand = " .$club->get_brand()." AND item_club_id = 0 OR item_brand=0 AND item_club_id = 0");
	$dd->display();
	?>	  	  
  </p>

<input type="hidden" name="kit_id" value="<?php echo $kit_id ?>"/>
<input type="hidden" name="action" value="add"/>
  </p>  
</form>
</div>	