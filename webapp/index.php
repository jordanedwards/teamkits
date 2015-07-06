<?php 
$public = true;
require("includes/init.php"); 

if($session->get_user_id() != "") {
	switch ($session->get_user_role()):
		case 1:
			header("location: /webapp/dashboard.php");
		break;
		case 3:
			header("location: /webapp/club_admin/dashboard_club.php");
		break;
		case 4:
			header("location: /webapp/dashboard_club_member.php");
		break;
	endswitch;
	exit;
} else {
	header("location: /login.php");
	exit;
}
?>
<!--<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Login | <?php echo $appConfig["app_title"]; ?></title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">    
    
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.min.css" rel="stylesheet">
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600" rel="stylesheet">
    <link href="css/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css">
    <link href="css/ui-lightness/jquery-ui-1.10.0.custom.min.css" rel="stylesheet">
    <link href="css/base-admin-3.css" rel="stylesheet">
    <link href="css/base-admin-3-responsive.css" rel="stylesheet">
    <link href="css/pages/dashboard.css" rel="stylesheet">   
    <link href="css/custom.css" rel="stylesheet">


</head>
<body>

<?php include(INCLUDES . "navbar.php");?>

<div class="main">
    <div class="container">
		<div class="row">

			<div class="col-md-12">	  
				  <?php  include(INCLUDES . "system_messaging.php");  ?>
			</div>
		</div>
			
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<h1>ADMINISTRATORS:</h1>						
				<form id="form1" action="actions/action_login_user.php" method="post" class="admin_table">
					<table class="admin_table">
				  <tr><td>Email:</td><td><input id="email" name="email" type="text" size="50" /></td></tr>
				  <tr><td>Password:</td><td><input id="password" name="password" type="password" /></td></tr>
				  <tr><td></td><td><input type="submit" value="Login" /></td></tr>
				  </table>
				</form>
					<br />
					<a href="forgot_password.php">Reset Your Password</a>				</div>
		</div>
		<br>
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<h1>CLUB ADMINISTRATORS:</h1>						
				<form id="form1" action="actions/action_login_user.php" method="post" class="admin_table">
					<table class="admin_table">
				  <tr><td>Email:</td><td><input id="email" name="email" type="text" size="50" /></td></tr>
				  <tr><td>Password:</td><td><input id="password" name="password" type="password" /></td></tr>
				  <tr><td></td><td><input type="submit" value="Login" /></td></tr>
				  </table>
				</form>
					<br />
					<a href="forgot_password.php">Reset Your Password</a>				</div>
		</div>
		<br>
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<h1>CLUB MEMBERS:</h1>						
				<form id="form1" action="actions/action_login_user.php" method="post" class="admin_table">
					<table class="admin_table">
				  <tr><td>Club ID:</td><td><input id="email" name="email" type="text" size="50" /></td></tr>
				  <tr><td></td><td><input type="submit" value="Login" /></td></tr>
				  </table>
				</form>
					<br />
					<a href="forgot_password.php">Reset Your Password</a>				</div>
		</div>				
	</div>
</div> 
    
<?php include(INCLUDES . "footer.php"); ?>
<?php include(INCLUDES_LIST); ?>

</body>
</html>-->