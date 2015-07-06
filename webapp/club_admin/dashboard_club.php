<?php 
$page_id = basename(__FILE__);
require("../includes/init.php"); 
require(INCLUDES . "acl_module.php"); 
$activeMenuItem = "Home";

include_once(CLASSES . "/class_club.php"); 
$club = new Club();	 
$club->get_by_user_id($currentUser->get_id()); 

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <?php  include(HEAD);  ?>
    <title>Dashboard | <?php echo $appConfig["app_title"]; ?></title>
<link href='http://fonts.googleapis.com/css?family=Josefin+Sans' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
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

<?php require(INCLUDES . "navbar_club.php");?>

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
			$query="SELECT item_name, item_price, promo_title, promo_description, promo_price, promo_expiry, promo_id, promo_description, promo_image FROM promo
			LEFT JOIN item ON promo.promo_item_id = item.item_id
			WHERE promo.is_active = 'Y' AND promo_expiry > now() AND promo_view_type = 'All' AND promo_sport = " . $club->get_sport() . "
			UNION
			SELECT item_name, item_price, promo_title, promo_description, promo_price, promo_expiry, promo_id, promo_description, promo_image FROM promo
			LEFT JOIN item ON promo.promo_item_id = item.item_id
			WHERE promo.is_active = 'Y' AND promo_expiry > now() AND promo_view_type = 'Brand' AND item_brand = (SELECT club_brand FROM club WHERE club_id =" . $club->get_id() . ") AND promo_sport = " . $club->get_sport() . "
			UNION
			SELECT item_name, item_price, promo_title, promo_description, promo_price, promo_expiry, promo_id, promo_description, promo_image FROM promo
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
						<td class="promo" colspan="2" rowspan="2"><h4>' . $row['promo_title'] . '</h4><blockquote style="border-left: 5px solid#ccc; background-color: #eee; font-size:1em;">' . $row['promo_description'] . '</blockquote>
						<p class="promo">Reg. Price: $' . number_format($row['item_price'],2) . '<br>
						Sale Price: <span class="red">$' . number_format($row['promo_price'],2) . '</span><br>
						Promotion ends: ' . $row['promo_expiry'] . '</span></p>						
											
						</td>
						<td class="promo" colspan="2" style="background: #fff; text-align:center; border: 2px solid #222;" ><a href="item_view.php?id=' .$row['promo_id'] . '">' . $promo_graphic. '</a></td>
					</tr>
					<tr style="border: 2px solid #222;">
						<td class="promo" style="text-align:center; border: 2px solid #222;"><a href="order_edit.php?id=0&promo_id=' . $row['promo_id'] . '"><img src="../../images/cta_sale.png" style="height: 50px"></i></a></td>										
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
						<h2>Most Recent Orders</h2>
					</div> <!-- /widget-header -->
					 <div class="widget-content">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>Id #</th>
									<th>Value</th>
									<th>Date</th>									
									<th>Status</th>
								</tr>
							</thead>
							<tbody>
<?php 
	$dm = new DataManager();
	$query="SELECT club_name, order_date_created, order_price, order_id, orderstatus_title FROM orders
	LEFT JOIN club ON orders.order_club_id = club.club_id
	LEFT JOIN orderstatus ON orders.order_status = orderstatus.orderstatus_id
	WHERE orders.is_active = 'Y' AND orders.order_club_id = " . $club->get_id() . "
	ORDER BY `order_date_created` DESC LIMIT 5";
	$result = $dm->queryRecords($query);
	if($result){
	while ($row = mysqli_fetch_array($result))
		{
			echo '
			<tr>
				<td><a href="orders_edit.php?id=' . $row['order_id'] . '">' . $row['order_id'] . '</a></td>
				<td>$' . number_format($row['order_price'],2) . '</td>
				<td>' . $row['order_date_created'] . '</td>						
				<td>' . $row['orderstatus_title'] . '</td>				
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
						
		</div>
	</div>
</div> 
    
<?php require(INCLUDES . "footer.php"); ?>
<?php require(INCLUDES_LIST);?>

</body>
</html>