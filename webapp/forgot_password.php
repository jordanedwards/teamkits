<?php 
$public = true;
require("includes/init.php"); 
$activeMenuItem = "Home";

//if($session->get_user_id() != "") {
//	header("location: dashboard.php");
//	exit;
//}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php  include(HEAD);  ?>
    <meta charset="utf-8">
    <title>Login | <?php echo $appConfig["app_title"]; ?></title>

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
				<h4>Enter the email address associated with your account. If your email address is found in the system we will send an email to your account with intructions on how to reset your password.</h4>
			<form id="form1" action="actions/action_forgot_password_admin.php" method="post">
				<table class="admin_table no-border">
					<tr><td>Email:</td><td><input id="email" name="email" type="text" size="50" /></td></tr>
					<tr><td></td><td><input type="submit" value="Reset Password" /></td></tr>
					<tr><td></td><td><a href="/">&laquo; Back</a></td>
				</table>
			</form>

			</div>
		</div>
				
	</div>
</div> 
    
<?php include(INCLUDES . "footer.php"); ?>
<?php require(INCLUDES_LIST);?>

</body>
</html>