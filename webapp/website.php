<?php 
require("includes/init.php"); 
$page_id = basename(__FILE__);
require(INCLUDES . "/acl_module.php");
include_once(CLASSES . "/class_user.php"); 
include(CLASSES . "/class_payment.php");
$activeMenuItem = "Website";
 ?>
	
<!DOCTYPE html>
<html lang="en">
  <head>
	<?php  include(HEAD);  ?>
    <title><?php  echo $appConfig["app_title"];  ?> | Payment List</title>
  </head>

  <body>

<?php  require(INCLUDES . "navbar.php");  ?>

<div class="main">
  <div class="container">
  
    <div class="row">
        <div class="col-md-12">
        <?php  include(INCLUDES . "system_messaging.php");  ?>
        <h1>Webpage settings</h1>
		</div>
	</div>
		
     <div class="col-md-12">
	 	<h3>in development...</h3>
	 </div>
  </div><!-- /container -->
</div>


<?php  include(INCLUDES. "footer.php");  ?>
<?php  include(INCLUDES_LIST);  ?>
  
  </body>
</html>