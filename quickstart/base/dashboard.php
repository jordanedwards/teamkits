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
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">    
    
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <link href="./css/bootstrap-responsive.min.css" rel="stylesheet">
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600" rel="stylesheet">
    <link href="./css/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css">
    <link href="./css/ui-lightness/jquery-ui-1.10.0.custom.min.css" rel="stylesheet">
    <link href="./css/base-admin-3.css" rel="stylesheet">
    <link href="./css/base-admin-3-responsive.css" rel="stylesheet">
    <link href="./css/pages/dashboard.css" rel="stylesheet">   
    <link href="./css/custom.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
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

<script src="./js/libs/jquery-1.9.1.min.js"></script>
<script src="./js/libs/jquery-ui-1.10.0.custom.min.js"></script>
<script src="./js/libs/bootstrap.min.js"></script>

</body>
</html>