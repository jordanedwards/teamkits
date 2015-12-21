<?php 
$page_id = basename(__FILE__);
$public=true;
require("../includes/init.php"); 
//require(INCLUDES . "acl_module.php"); 
$activeMenuItem = "Home";

require_once(CLASSES . "class_user.php"); 	
require_once(CLASSES . "/class_club.php"); 
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
    <title>Club member login | <?php echo $appConfig["app_title"]; ?></title>
<link href='https://fonts.googleapis.com/css?family=Josefin+Sans' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
<style>
.widget .widget-header.promo-header {
	height:auto;
}
.promo-header h2{
  padding: 10px 0 5px 10px;
    font-size: 1.7em;
 }
</style>
</head>
<body>

<?php require(INCLUDES . "navbar.php");?>

<div class="main">
    <div class="container">
		<div class="row">
			<div class="row">
				<div class="col-md-12">	  
					  <?php  include(INCLUDES . "system_messaging.php");  ?>
				</div>
			</div>
			
			<div class="col-md-8">
				<div class="widget widget-table action-table">
						
					<div class="widget-header promo-header">
						<h2>Current Promotions</h2>
					</div> <!-- /widget-header -->
					
					<div class="widget-content">
						<table class="table table-bordered promo_table">
							<!--<thead>
								<tr>
									<th>Item</th>
									<th>Reg. Price</th>									
									<th>Sale Price</th>
									<th>Expires</th>
									<th></th>									
								</tr>
							</thead>-->
							<tbody>
							<?php 
			$dm = new DataManager();
			$query="SELECT * FROM promo
			LEFT JOIN item ON promo.promo_item_id = item.item_id
			WHERE promo.is_active = 'Y' AND promo_expiry > now() AND promo_view_type = 'All' AND promo_sport = " . $club->get_sport() . "
			UNION
			SELECT * FROM promo
			LEFT JOIN item ON promo.promo_item_id = item.item_id
			WHERE promo.is_active = 'Y' AND promo_expiry > now() AND promo_view_type = 'Brand' AND item_brand = (SELECT club_brand FROM club WHERE club_id =" . $club->get_id() . ") AND promo_sport = " . $club->get_sport() . "
			UNION
			SELECT * FROM promo
			LEFT JOIN item ON promo.promo_item_id = item.item_id
			WHERE promo.is_active = 'Y' AND promo_expiry > now() AND promo_view_type = 'Club Exclusive' AND promo_club_id = " . $club->get_id() . "
			ORDER BY `promo_expiry` ASC			
			";
			
			//echo $query;
			$result = $dm->queryRecords($query);
			
			if($result){
			while ($row = mysqli_fetch_array($result)){
				if ($row['promo_image'] != ""){
					$promo_graphic = "<img src = '/webapp/images/promo/" . $row['promo_image'] . "' style='  max-height: 150px;'>";
				}else {
				//Change to pull uploaded graphic from item profile:
					$promo_graphic = "<img src = '/webapp/images/itemimages/" . $row['promo_image'] . "' style='  max-height: 150px;'>";
				}
					echo '
					<tr style="border: 2px solid #222;">
						<td class="promo" colspan="2" rowspan="2"><h4 style="text-transform: capitalize;">' . $row['promo_title'] . '</h4><blockquote style="border-left: 5px solid#ccc; background-color: #eee; font-size:1em;">' . $row['promo_description'] . '</blockquote>
						<p class="promo">Reg. Price: $' . number_format($row['item_price'],2) . '<br>
						Sale Price: <span class="red">$' . number_format($row['promo_price'],2) . '</span><br>
						Promotion ends: ' . $row['promo_expiry'] . '</span></p>						
											
						</td>
						<td class="promo" colspan="2" style="background: #fff; text-align:center; border: 2px solid #222;" ><a href="item_view.php?id=' .$row['promo_item_id'] . '">' . $promo_graphic. '</a></td>
					</tr>
					<tr style="border: 2px solid #222;">
						<td class="promo" style="text-align:center; border: 2px solid #222;">
						<span><input id="promoQuantity_' . $row['promo_id']. '" placeholder="Quantity" class="form-control inline auto-width">
						';
	
	$dd = New DropDown();
	$dd->set_table("itemSize");
	$dd->set_name("promoSize_".$row['promo_id']);		
	$dd->set_name_field("itemSize_name");
	$dd->set_class_name("form-control inline auto-width");
	$dd->set_index_name("itemSize_name");
	$dd->set_where("itemSize_item_id = ".$row['promo_item_id']);		
	$dd->set_order("ASC");						
	$dd->set_active_only(true);
	$dd->set_placeholder("Size");		
	$dd->display();
						
						echo '</span>
						<a href="javascript;;" class="add-promo-item" data-item-id="' . $row['promo_item_id'] . '" data-promo-id="' . $row['promo_id'] . '"><img src="../../images/cta_sale.png" style="height: 50px"></i></a></td>										
					</tr>
					';
				}
			}
							?>
								</tbody>
							</table>
						
					</div>
					
				</div>
			</div>
			
			<div class="col-md-4">
				<div class="widget widget-table action-table">
						
					<div class="widget-header promo-header">
						<h2>Club Orders</h2>
					</div> <!-- /widget-header -->
					 <div class="widget-content">
					 <div style="    padding: 5px;
    text-align: center;
    background-color: lightyellow;"><h5>These are club orders where payment is needed from club members. Click on any order below to make your payment:</h5></div>
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>Order#</th>
									<th>Deadline Date</th>									
									<th>Status</th>
								</tr>
							</thead>
							<tbody>
<?php 
	$dm = new DataManager();
	$query="SELECT club_name, order_deadline, order_id, orderstatus_title FROM orders
	LEFT JOIN club ON orders.order_club_id = club.club_id
	LEFT JOIN orderstatus ON orders.order_status = orderstatus.orderstatus_id
	WHERE orders.is_active = 'Y' AND orders.order_club_id = " . $club->get_id() . " AND orders.order_type ='member' AND orderstatus.orderstatus_title ='Waiting for payment' 
	ORDER BY `order_date_created` DESC LIMIT 5";
	$result = $dm->queryRecords($query);
	if($result){
	while ($row = mysqli_fetch_array($result))
		{
			echo '
			<tr>
				<td style="vertical-align: middle;">' . $row['order_id'] . '</td>
				<td style="vertical-align: middle;">' . $row['order_deadline'] . '</td>						
				<td style="vertical-align: middle;"><a href="order_view.php?id=' . $row['order_id'] . '"><input type="button" class="btn btn-success" value="View Order"></a></td>				
			</tr>
			';
		}
	}
?>
								</tbody>
							</table>
						
					</div>		
				</div>
				
				<!--<div class="widget widget-table action-table">
						
					<div class="widget-header promo-header">
						<h2>Custom Kits</h2>
					</div> 
					 <div class="widget-content">
		<table class="table table-bordered">
			<thead>
			<tr><th>Title:</th></tr>	
			</thead>
			
			<tbody>
		 <?php 
		 	$dm = new DataManager(); 
			$strSQL = "SELECT * from kit 
			WHERE club_id=" . $club->get_id() . "
			AND is_active = 'Y'
			";
			
			$result = $dm->queryRecords($strSQL);	
			if ($result):
				while($row = mysqli_fetch_assoc($result)):
					echo '<tr><td><a href="kit_view.php?id=' . $row['id'] .'">' . $row['title'] . '</a></td></tr>';
				endwhile;									
			endif;
		 ?>		
			</tbody>
		</table>
						
					</div>		
				</div>-->
			</div>
						
		</div>
	</div>
</div> 
    
<?php require(INCLUDES . "footer.php"); ?>
<?php require(INCLUDES_LIST);?>
<script>
$(function() {
	// promom item clicked:
	$(".add-promo-item").on("click", function (e) {
		e.preventDefault();
		var itemId = $(this).data("item-id");
		var promoId = $(this).data("promo-id");
		var itemQuantity = $('#promoQuantity_'+promoId).val();
		var itemSize = $('#promoSize_'+promoId).val();
	//	console.log("item: "+itemId+" quantity: "+itemQuantity+" size: "+itemSize);
		$.ajax({
		  	type: "POST",
		  	url: "ajax_cart.php",
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