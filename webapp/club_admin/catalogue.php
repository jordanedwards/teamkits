<?php 
$page_id = basename(__FILE__);
require("../includes/init.php"); 
require(INCLUDES . "acl_module.php"); 
$activeMenuItem = "Catalogue";

include_once(CLASSES . "/class_club.php"); 
$club = new Club();	 
$club->get_by_user_id($currentUser->get_id()); 		
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
		<?php 
			$dm = new DataManager();
			$query="SELECT *, (SELECT itemImage_url FROM itemImage WHERE itemImage_item_id = item_id LIMIT 1) AS image FROM item
			WHERE item_club_id =".$club->get_id()." AND is_active = 'Y' OR item_club_id=0 AND item_brand =" . $club->get_brand() . " AND is_active = 'Y' OR item_club_id=0 AND item_brand =0 AND item_name NOT LIKE '%Shipping charges%' AND is_active = 'Y'
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

					<a href="orders_edit.php?id=0&additem[]=' .$row['item_id'] .'"><img src="/images/cta_add_to_order.png" style="    width: 150px;"></a>
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
	</body>	
</html>