<?php 
$page_id = basename(__FILE__);
$public = true;
require_once("../includes/init.php"); 
//require(INCLUDES . "acl_module.php"); 
$activeMenuItem = "Catalogue";

require_once(CLASSES . "class_user.php"); 	
require_once(CLASSES . "class_club.php");
$club = new Club();	 
$club->get_by_id($session->get_club()); 

if (!$club->get_id() >0){
		$session->setAlertColor("red");			
		$session->setAlertMessage("Please login");
		header("location: /login.php");		
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <?php  include(HEAD);  ?>
    <title>Catalogue | <?php echo $appConfig["app_title"]; ?></title>
<link href='https://fonts.googleapis.com/css?family=Josefin+Sans' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
<link href="../css/style-208.css" rel="stylesheet">
<style>
.catalogue-images{
	height: 300px;
    margin-left: auto;
    margin-right: auto;
	padding:10px;	
}
</style>
</head>
<body>
<?php require(INCLUDES . "navbar.php");?>
<div class="ui-208">

	<div class="container">
		<div class="row">
			<div class="col-md-12">	  
				  <?php  include(INCLUDES . "system_messaging.php");  ?>
			</div>
		</div>
			
		<div class="row">
		<?php 
			$dm = new DataManager();
			$query="SELECT *, (SELECT itemImage_url FROM itemImage WHERE itemImage_item_id = item_id LIMIT 1) AS image 
			FROM item
			WHERE item_club_id =".$club->get_id()." OR item_club_id=0 AND item_brand =" . $club->get_brand() . " OR item_club_id=0 AND item_brand =0 AND item_name NOT LIKE '%Shipping charges%'
			ORDER BY `item_name` ASC ";
			$result = $dm->queryRecords($query);
			if($result){
			while ($row = mysqli_fetch_array($result))
				{
				if ($row['image'] != null && file_exists("../images/itemimages/".$row['image'])){
					$image = "../images/itemimages/".$row['image'];
				} else {
					$image = "/images/no_image.png";
				}
					echo '
			<div class="col-md-4 col-sm-6">
			
				<div class="ui-item">
				  <a href="#"><img src="' .$image . '" alt="" class="img-responsive catalogue-images" /></a>
				  <div class="content">
						<h3><a href="#">' . $row['item_name'] .'</a></h3>
						<p>' . $row['item_description'] .'</p>
						<tr style="border: 2px solid #222;">
							<td class="promo" style="text-align:center; border: 2px solid #222;">
							<span><input id="qty_' . $row['item_id']. '" placeholder="Quantity" class="form-control inline" style="width: 80px;">
							';
							
							$dd = New DropDown();
							$dd->set_table("itemSize");
							$dd->set_name("size_".$row['item_id']);		
							$dd->set_name_field("itemSize_name");
							$dd->set_class_name("form-control inline auto-width");
							$dd->set_index_name("itemSize_name");
							$dd->set_where("itemSize_item_id = ".$row['item_id']);		
							$dd->set_order("ASC");
							$dd->set_active_only(true);
							$dd->set_placeholder("Size");		
							$dd->display();
												
							echo '</span>
						</td>										
					</tr>
					<a class="add_to_cart add-item" data-item-id="' . $row['item_id'] .'" data-quantity="1"><img src="/images/cta_add_to_order.png" style="    width: 150px;"></a>
				  </div>
				</div>
			
			</div>
					';
				}
			}
			//echo $query;
		?>
	</div>
	</div>

</div>
		
<?php  include(INCLUDES . "/footer.php");  ?>
<?php  include(INCLUDES_LIST);  ?>	
		<!-- Respond JS for IE8 -->
		<script src="../js/respond.min.js"></script>
		<!-- HTML5 Support for IE -->
		<script src="../js/html5shiv.js"></script>
<script>
$(function() {
	// promom item clicked:
	$(".add-item").on("click", function (e) {
		e.preventDefault();
		var itemId = $(this).data("item-id");
		var itemSize = "";
		var itemQuantity = $('#qty_'+itemId).val();
		var itemSize = $('#size_'+itemId).val();
	//	console.log("item: "+itemId+" quantity: "+itemQuantity+" size: "+itemSize);
		$.ajax({
		  	type: "POST",
		  	url: "ajax_cart.php",
			//dataType: "json",
		  	data: { item_id: itemId, action:'add', item_quantity:itemQuantity, item_size:itemSize},
		  	success: function (html) {
				$('#alert_message').html(html);
				$('#alert_message').removeClass();				
				//$('#alert_message').addClass(data.alertColor);				
				$('#alert_message').show();
				//console.log(data);
			}
		});
	});
	
});
</script>	
	</body>	
</html>