
<script>

$(function() {
	$( "#image_preview_dialog" ).dialog({
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
			"Close": {
				click: function() {
						$( this ).dialog( "close" );
					},
				text: "Cancel",
				class: 'btn btn-primary'
			}
		}
	});


	$("#kits").on("click", ".item_view_link", function (e) {
		var orderItem = $(this).data("item-id");
		 
		$.ajax({
			url: "/webapp/club_admin/ajax_image_carousel.php?id="+orderItem,	
			success: function (html) {	
			  $('#preview_window').html(html);
			  $("#image_preview_dialog").dialog("open");
			}		
		});
	});
	

	
});

	
</script>


<div id="image_preview_dialog" title="Item" style="display:none">
		<div id="preview_window">
		
		</div>
</div>	