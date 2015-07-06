<script>

$(function() {
	$( "#note_add_dialog" ).dialog({
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
					var newItem = $('#newNoteForm').serialize();
					//console.log(newItem);
						$.ajax({
						url: "ajax/ajax_note_item.php?"+newItem,	
						success: function (html) {	
							$('#note_table').append(html);
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
	$(".add-note").on("click", function (e) {
		e.preventDefault();
		$('#note_add_dialog').dialog('open');
	});
	
});
</script>


<div id="note_add_dialog" title="Add Note" style="display:none">
<form id="newNoteForm" method="post">
<p>
<textarea rows="8" class="form-control" name="clubNote" style="width:100%"></textarea>
<input type="hidden" name="club_id" value="<?php echo  $club_id ?>"/>
<input type="hidden" name="action" value="add"/>
  </p>  
</form>
</div>	