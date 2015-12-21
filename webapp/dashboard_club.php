<?php 
$page_id = basename(__FILE__);
require("includes/init.php"); 
require(INCLUDES . "acl_module.php"); 
$activeMenuItem = "Home";
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <?php  include(HEAD);  ?>
    <title>Dashboard | <?php echo $appConfig["app_title"]; ?></title>

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
			
			<div class="col-md-6">
				<div class="widget widget-table action-table">
						
					<div class="widget-header">
						<i class="fa fa-th-list"></i>
						<h3>Current Promotions</h3>
					</div> <!-- /widget-header -->
					 <!-- /widget-content -->		
				</div>
			</div>
			
			<div class="col-md-6">
				<div class="widget widget-table action-table">
						
					<div class="widget-header">
						<i class="fa fa-th-list"></i>
						<h3>Most Recent Orders</h3>
					</div> <!-- /widget-header -->
					 <!-- /widget-content -->		
				</div>
			</div>
						
		</div>
	</div>
</div> 
    
<?php require(INCLUDES . "footer.php"); ?>
<?php require(INCLUDES_LIST);?>

</body>
</html>