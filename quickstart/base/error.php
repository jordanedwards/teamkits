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


    <hr style="height:60px">

 <!-- /subnavbar -->


<div id="wrapper">
<div class="container" style="min-height: 500px;">
	
	<div class="row">
		
		<div class="col-md-12">
			
			<div class="error-container">
				<h1>Oops!</h1>
				
				<h2>System Error</h2>
				
	          <?php  include("includes/system_messaging.php");  
			  ?>
				
				<div class="error-actions">

					<a href="contact.php" class="btn btn-default btn-lg">
						<i class="icon-envelope"></i>
						&nbsp;
						Contact Support						
					</a>
					
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