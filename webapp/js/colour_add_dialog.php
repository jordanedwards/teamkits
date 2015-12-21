<script>

$(function() {
	$( "#colour_add_dialog" ).dialog({
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
					var newItem = $('#newcolourForm').serialize();
					//console.log(newItem);
						$.ajax({
						url: "/webapp/ajax/ajax_colour_item.php?"+newItem,	
						success: function (html) {	
							$('#colour_table').append(html);
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
	$(".add-colour").on("click", function (e) {
		e.preventDefault();
		$('#colour_add_dialog').dialog('open');
	});
	
	
});
</script>


<div id="colour_add_dialog" title="Add Colour" style="display:none">
<form id="newcolourForm" method="post">
<h4>Set title:</h4>
<p>
<input type="text" name="title" placeholder="Set, i.e: Home jersey" class="form-control inline"/> 
</p>
<p class="small">* Use the same title for colours that belong together.</p>
 <p>
 <h4 style="display:inline-block">Please select your colour:</h4>
 <input type="color" name="hex_code" id="html5colorpicker" class="form-control inline colorpicker" onchange="clickColor(0, -1, -1, 5)" value="" ><br>
 <h4 style="display:inline-block">Or colour code:</h4>
<input type="text" name="code" placeholder="Colour code" class="form-control inline" style="width:200px"/> 
</p>


<input type="hidden" name="club_id" value="<?php echo  $club->get_id() ?>"/>
<input type="hidden" name="action" value="add"/>
</form>
</div>	