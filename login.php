<?php 
$public = true;
require("webapp/includes/init.php"); 
$activeMenuItem = "Home";

if($session->get_user_id() != "") {
	switch ($session->get_user_role()):
		case 1:
			header("location: /webapp/dashboard.php");
		break;
		case 3:
			header("location: /webapp/club_admin/dashboard_club.php");
		break;
		case 4:
			header("location: /webapp/members/dashboard_member.php");
		break;
	endswitch;
	exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php  include(HEAD);  ?>
    <!--<meta charset="utf-8">-->
    <title>Login | <?php echo $appConfig["app_title"]; ?></title>
    <!--
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">    
    
    <link href="webapp/css/bootstrap.min.css" rel="stylesheet">
    <link href="webapp/css/bootstrap-responsive.min.css" rel="stylesheet">
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600" rel="stylesheet">
    <link href="webapp/css/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css">
    <link href="webapp/css/ui-lightness/jquery-ui-1.10.0.custom.min.css" rel="stylesheet">
    <link href="webapp/css/base-admin-3.css" rel="stylesheet">
    <link href="webapp/css/base-admin-3-responsive.css" rel="stylesheet">
    <link href="webapp/css/pages/dashboard.css" rel="stylesheet">   
    <link href="webapp/css/custom.css" rel="stylesheet">-->

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body>

<?php include(INCLUDES . "navbar.php");?>

<div class="main">
    <div class="container" style="padding-top:40px;">
		<div class="row">

			<div class="col-md-12">	  
				  <?php  include(INCLUDES . "system_messaging.php");  ?>
			</div>
		</div>
			
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<h1>CLUBS & ADMINISTRATORS:</h1>						
				<form id="form1" action="webapp/actions/action_login_user.php" method="post" class="admin_table">
					<table class="admin_table">
				  <tr><td>Email:</td><td><input id="email" name="email" type="email" size="50" required/></td></tr>
				  <tr><td>Password:</td><td><input id="password" name="password" type="password" required/></td></tr>
				  <?php if (isset($_REQUEST['redirect'])){?><input type="hidden" name="redirect" value="<?php echo $_REQUEST['redirect'] ?>">	<?php } ?>	
				  <tr><td></td><td><input type="submit" value="Login" /></td></tr>
				  </table>
				</form>
					<br />
					<a href="webapp/forgot_password.php">Reset Your Password</a>				</div>
		</div>
		<br>
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<h1>CLUB MEMBERS:</h1>						
				<form id="form1" action="webapp/members/actions/action_login.php" method="post" class="admin_table">
					<table class="admin_table">
				  <tr><td>Club Code:</td><td><input id="club_code" name="club_code" type="text" size="50" required/></td></tr>
				  <input type="hidden" name="login_type" value="club_member">	
				  <?php if (isset($_REQUEST['redirect'])){?><input type="hidden" name="redirect" value="<?php echo $_REQUEST['redirect'] ?>">	<?php } ?>	
				  			  
				  <tr><td></td><td><input type="submit" value="Login" /></td></tr>
				  </table>
				</form>
					<br />
					<a href="webapp/forgot_password.php">Reset Your Password</a>				</div>
		</div>				
	</div>
</div> 
    
<?php include(INCLUDES . "footer.php"); ?>
<?php require(INCLUDES_LIST);?>

</body>
</html>