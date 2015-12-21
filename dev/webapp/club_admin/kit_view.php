<?php 
require("../includes/init.php"); 
$page_id = basename(__FILE__);
$activeMenuItem = "Kit";				
require(INCLUDES . "/acl_module.php");
include(CLASSES . "/class_club.php"); 
require_once(CLASSES . "/class_kit.php"); 
 
if(!isset($_GET["id"]) && !isset($_GET['club_id'])) {
	$session->setAlertMessage("Can not edit - the ID is invalid. Please try again.");
	$session->setAlertColor("yellow");
	header("location:" . BASE_URL . "/index.php");
	exit;
}

$club = new Club();
	 
// Look up which club is associated with this user, then create club object
$club->get_by_user_id($currentUser->get_id()); 
	
// load the kit		
$kit_id = $_GET["id"];
$kit = new Kit();

if ($_GET["id"] ==0){
	// Change this to pass a parent value if creating a new record:
	$kit->set_club_id($_GET['club_id']);
} else {
	$kit->get_by_id($kit_id);
}

// Block access if the user is not the owner:
if($kit->get_club_id() != $club->get_id()) {
	$session->setAlertMessage("Can not view - Club Id does not match.");
	$session->setAlertColor("yellow");
	header("location:" . BASE_URL . "/index.php");
	exit;
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
	<?php  include(HEAD);  ?>
    <meta name="description" content="">

    <title><?php   echo $appConfig["app_title"];  ?> | Kit Edit</title>
	<link href="../css/carousel.css" rel="stylesheet">    
	
	<style>
#preview_window{
  width: 100%;
  height: 400px;
  border: 2px solid #222;
  background: url('/images/mockup_preview.gif') #ccc;
  background-repeat: no-repeat;
  background-position: center;
}
.imagePreview{
cursor:pointer;
  background: #fff;
  padding: 5px;
}
.imagePreview:hover{
	text-decoration:none;
}
.carousel-caption { 
color:#222;
}
h1 {
background:#DDAB30;
border:1px solid #333;
color:#333;

}
</style>	
  </head>

  <body>

<?php  require(INCLUDES . "navbar.php");  ?>
<div class="main">

    <div class="container">
      <div class="row">
        <div class="col-md-12">
        <?php  include(INCLUDES . "system_messaging.php");  ?>

        <h1><?php echo $kit->get_title(); ?></h1> 
		
		<div id="alert_message" class="white">
			<?php  echo $kit->get_note();  ?>
		</div>	
			       
		</div>
	</div>

	<div class="row">
		<div class="col-md-6">
		<table class="admin_table" id="kits">
			<thead>
			<tr><th colspan="2">Kit Items:<i class="fa fa-plus-circle fa-lg add-icon add-item"></i></th></tr>
			<tr><th>Item:</th><th>Price</th></tr>	
			</thead>
			
			<tbody id="kit_items_table">
		 <?php 
			$dm = new DataManager(); 
			$strSQL = "SELECT *, kitItem.id AS kitItemId from kitItem 
			LEFT JOIN item ON kitItem.item_id = item.item_id 
			WHERE kitItem.is_active = 'Y' AND kitItem.build_id = ". $kit->get_id();						
		
			$result = $dm->queryRecords($strSQL);	
			if ($result):
				while($row = mysqli_fetch_assoc($result)):
					echo '<tr><td>' . $row['item_name'] . '</td><td>$' . number_format($row['item_price'],2) . '</td></tr>';
					$itemArray[$row['item_id']] = $row['item_id'];
				endwhile;									
			endif;
		 ?>		
			</tbody>
		</table>
		<br>
		<a href="orders_edit.php?id=0&kit=<?php echo $kit->get_id();?>" class="btn btn-success">Order this kit</a>
		</div>
		<div class="col-md-6">
			<div id="preview_window">
			<?php 
if (sizeof($itemArray)>0){			
foreach ($itemArray as $key => $val){
		//	echo $key . " - " . $val."<br>";
			$strSQL = "SELECT * FROM `itemImage` WHERE `itemImage_item_id` = " . $key . " AND is_active = 'Y'";
			$result = $dm->queryRecords($strSQL);
			if ($result):
				$num_rows = mysqli_num_rows($result);
				if ($num_rows > 0):
					while ($line = mysqli_fetch_assoc($result)):
						$images[$line['itemImage_url']] = $line['itemImage_description'];
					endwhile;
				endif;
			endif;
	}
}
	//print_r($images);
?>
<div id="myCarousel" class="carousel slide" data-ride="carousel">
      <!-- Indicators -->
      <ol class="carousel-indicators">
        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
	<?php 
	$x =1;
		while($x < $num_rows) {
echo '<li data-target="#myCarousel" data-slide-to="' . $x . '" class=""></li>
	';
			$x++;
		} 
?></ol>
      <div class="carousel-inner" role="listbox">
	  <?php 
	$active = " active ";
	$str = "";

if (sizeof($images)>0){			 
	 foreach ($images as $key => $val){
		$str .='<div class="item '. $active . '">
          <img src="/webapp/images/itemimages/' . $key . '" alt="' . $val .'">
          <div class="container">
            <div class="carousel-caption">
              <p>' . $val .'</p>
            </div>
          </div>
        </div>';
		$active = "";
	} 
}
	echo $str;
	?>
      </div>
      <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </div>
<?php /*
else:
// No images available:
echo "<h3 style='text-align: center; padding-top: 40px;'>No images available</h3>";
endif;

endif; */
?>
			
			</div>
		</div>
    </div> 

</div><!-- /container -->
</div>

<?php  include(INCLUDES . "/footer.php");  ?>
<?php  include(INCLUDES_LIST);  ?>	

  </body>
</html>