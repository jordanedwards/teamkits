<?php 
// 1. Lookup item images & descriptions
// 2. Populate slides
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
          <img src="../images/itemimages/' . $key . '" alt="' . $val .'">
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
     <!--   <div class="item active">
          <img class="first-slide" src="../images/itemimages/jersey-macron.jpg" alt="First slide">
          <div class="container">
            <div class="carousel-caption">
              <p>Barcelona sample jerseys</p>
            </div>
          </div>
        </div>
        <div class="item">
          <img src="../images/itemimages/Soccer-Ball.jpg" alt="Second slide">
          <div class="container">
            <div class="carousel-caption">
              <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
            </div>
          </div>
        </div>
        <div class="item">
          <img class="third-slide" src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" alt="Third slide">
          <div class="container">
            <div class="carousel-caption">
              <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
            </div>
          </div>
        </div> -->
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