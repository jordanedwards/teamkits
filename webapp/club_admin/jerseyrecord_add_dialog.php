<script>

$(function() {
	$( "#jerseyrecord_add_dialog" ).dialog({
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
					var formData = $('#newComponentForm').serialize();
					$.ajax({
						url: "ajax_jerseyrecord.php?"+formData,	
						dataType: "json",
						success: function (data) {	
							var newLine = '<tr><td><a href="jerseyrecord_edit.php?id=' + data.id +'"><i class="fa fa-edit fa-lg"></i></a>&nbsp;&nbsp;<a href="actions/action_jerseyrecord_edit.php?action=delete&page_id=jerseyrecord_edit.php&id=' + data.id + '" onclick="return confirm(\'Delete?\');" class="editing"><i class="fa fa-times-circle fa-lg"></i></a></td><td>' + data.name + '</td><td>' + data.number + '</td><td>' + data.status + '</td></tr>';
							
							$('#jerseyrecord_table').append(newLine);
							$("#newComponentForm").get(0).reset();
						}	
					});			
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
		$('#jerseyrecord_add_dialog').dialog('open');
	});
	
});
	
</script>


<div id="jerseyrecord_add_dialog" title="Add jersey number/name" style="display:none">
<form id="newComponentForm" method="post">
<p>
<input type="number" placeholder="Jersey #" style="width: 90%;" class="form-control inline" name="number"/> 
</p>
<p>
<input type="text" placeholder="Jersey Name" style="width: 90%;" class="form-control inline" name="name"/> 
</p>
<input type="hidden" name="orderitem_id" id="orderitem_id"  value="<?php echo  $orderitem->get_id() ?>"/>
<input type="hidden" name="action" value="add"/>
 </p>  
</form>
</div>	