<script>

$(function() {
	$( "#contact_add_dialog" ).dialog({
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
					var newItem = $('#newcontactForm').serialize();
					//console.log(newItem);
						$.ajax({
						url: "ajax/ajax_contact_item.php?"+newItem,	
						success: function (html) {	
							$('#contact_table').append(html);
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
	$(".add-contact").on("click", function (e) {
		e.preventDefault();
		$('#contact_add_dialog').dialog('open');
	});
	
	$("#clubContact_tel").mask("(999) 999-9999");
	
});
</script>


<div id="contact_add_dialog" title="Add Contact" style="display:none">
<form id="newcontactForm" method="post">
<h4>Please fill in:</h4>
<p>
<input type="text" placeholder="Name" class="form-control inline" name="name"/> 
</p>
 <p>
<input type="text" placeholder="Position" class="form-control inline" name="position"/>
</p>
 <p>
<input type="tel" placeholder="Telephone #" class="form-control inline" name="tel" id="clubContact_tel"/>
</p>
 <p>
<input type="email" placeholder="Email address" class="form-control inline" name="email"/>
</p>
<p>
<input type="hidden" name="club_id" value="<?php echo  $club_id ?>"/>
<input type="hidden" name="action" value="add"/>
  </p>  
</form>
</div>	