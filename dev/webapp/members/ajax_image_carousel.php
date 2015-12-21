<?php 
// 1. Lookup item images & descriptions
// 2. Populate slides
$public = true;
require("../includes/init.php"); 
$item_id = $_GET['id'];

$dm = new DataManager();
$strSQL = "SELECT * FROM `itemImage` WHERE `itemImage_item_id` = " . $item_id . " AND is_active = 'Y'";
$result = $dm->queryRecords($strSQL);
			
if ($result):
	$num_rows = mysqli_num_rows($result);
if ($num_rows > 0):
	while ($line = mysqli_fetch_assoc($result)):
		$images[$line['itemImage_url']] = $line['itemImage_description'];
	endwhile;	
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
		
	echo $str;
	?>
      </div>
      <a class="left carousel-control" href="http://getbootstrap.com/examples/carousel/#myCarousel" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="right carousel-control" href="http://getbootstrap.com/examples/carousel/#myCarousel" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </div>
<?php 
else:
// No images available:
echo "<h3 style='text-align: center; padding-top: 40px;'>No images available</h3>";
endif;

endif; 
?>