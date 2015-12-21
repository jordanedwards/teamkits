<script>

$(function() {
	$( "#image_add_dialog" ).dialog({
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
			/*		var newItem = $('#newImageForm').serialize();
					$.ajax({
						url: "ajax/ajax_item_image.php?"+newItem,	
						success: function (html) {	
							$('#images_table').append(html);
						}	
					});*/
			/*	var newItem = $('#newImageForm').serializeArray();
				$.ajax({
				  type: "POST",
				  url: "ajax/ajax_item_image.php",
				  data: newItem,
				  success: function (html) {	
						$('#images_table').append(html);
					},
				});	*/
					$('#newImageForm').submit();	
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
	$(".add-image").on("click", function (e) {
		e.preventDefault();
		$('#image_add_dialog').dialog('open');
	});
});
</script>


<div id="image_add_dialog" title="Image upload" style="display:none">
	<form id="newImageForm" enctype="multipart/form-data"  method="post" action="actions/action_item_image.php">
		<input type="hidden" name="item_id" value="<?php echo $item_id ?>"/>
		<input type="hidden" name="action" value="add"/>
         <table class="admin_table">
				<tr>
            		<td style="background: lightsteelblue;">
					<label for="upload">Select a file...</label><input type="file" name="upload" size="30">
					<br>
					<input type="text" placeholder="Description" style="width: 100%;" class="form-control inline" name="itemImage_description"/> 
					<br>					
					<!--<input type="submit" name="uploadSubmit" value="Upload">-->
					<p class="small_text">
					* Max upload size 600kb</p></td>
				</tr>								 		
		</table>
          <br />
        </form>
		
</div>	