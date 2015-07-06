<?php 
$page_id = basename(__FILE__);
include("includes/init_public.php"); 
$activeMenuItem = "Home";

?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Error Page</title>

<?php //include("/site/includes/head.php");?>

</head>

<body>

 <!-- /subnavbar -->


<div id="wrapper">
<div class="container" style="min-height: 500px;">
	
	<div class="row">
		
		<div class="col-md-12">
			
			<div class="error-container">
				
				<h2>System Error:</h2>
				<h3>Support has been notified of this and the problem has been queued for repair.</h3>
	          <?php  include(INLCUDES . "system_messaging.php");  
			  ?>
				
				<div class="error-actions">

<p>
<?php echo $_GET['msg'] ?>
</p>

<input type="button" value="Back" onClick="window.location ='<?php echo $_SERVER["HTTP_REFERER"];?>'">					
				</div> <!-- /error-actions -->
							
			</div> <!-- /error-container -->			
			
		</div> <!-- /span12 -->
		
	</div> <!-- /row -->
	
</div> <!-- /container -->
</div>

	<?php include("includes/footer.php")?>    
	<script src="js/bootstrap.min.js"></script>
</body>
</html>