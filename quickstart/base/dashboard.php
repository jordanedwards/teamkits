<?php 
$page_id = basename(__FILE__);
require("includes/init.php"); 
require(INCLUDES . "acl_module.php"); 
$activeMenuItem = "Home";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Dashboard | <?php echo $appConfig["app_title"]; ?></title>
	<?php require(HEAD) ?>
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
			
			<div class="col-md-12">
				<div class="widget widget-table action-table">
						
					<div class="widget-header">
						<i class="fa fa-th-list"></i>
						<h3>Most recent activity</h3>
					</div> <!-- /widget-header -->
					
					 <!-- /widget-content -->
				
				</div>
				
				
			</div>
			
							
		</div>
	</div>
</div> 
    
<?php require(INCLUDES . "footer.php"); ?>
<?php require(INCLUDES_LIST); ?>
</body>
</html>