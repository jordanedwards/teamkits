<?php 
$page_id = basename(__FILE__);
require("includes/init.php"); 
$activeMenuItem = "Home";

	// Check if user is logged in
	if($session->get_user_id() != "") {
	// ****************************************** SET TO YOUR HOME PAGE **********************************
		header("location: dashboard.php");
		exit;
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require(HEAD) ?>
    <title>Reset Password | <?php echo $appConfig["app_title"]; ?></title>
    <link href="./css/signin.css" rel="stylesheet">   

</head>

<body>

<?php require(INCLUDES . "navbar.php");?>
	
		<div class="container">
			<div class="col-md-6 col-md-offset-3">	  
			  <?php  include(INCLUDES . "system_messaging.php");  ?>
		</div>
		
<div class="account-container">
	
	<div class="content clearfix">
				

		<form action="actions/action_forgot_password_admin.php" method="post">
		
			<h1>Password Reset</h1>		
			
			<div class="login-fields">
				
				<p>Enter the email address associated with your account. If your email address is found in the system we will send an email to your account with intructions on how to reset your password</p>
				
				<div class="field">
					<label for="email">Username:</label>
					    <div class="input-group">
						  <div class="input-group-addon"><i class="fa fa-user"></i></div>
						  <input type="text" class="form-control input-lg inline" id="email" name="email" placeholder="Username" required>
						</div>
					<!--<input type="email" id="email" name="email" value="" placeholder="Username" class="form-control input-lg username-field" >-->
				</div> <!-- /field -->
				
			</div> <!-- /login-fields -->
			
			<div class="login-actions">
									
				<button class="login-action btn btn-primary">Reset Password</button>
				
			</div> 
			
  </div>
			
		</form>
		
	</div> <!-- /content -->
	
</div> <!-- /account-container -->
    
<?php require(INCLUDES . "footer.php"); ?>
<?php require(INCLUDES_LIST); ?>
<script src="./scripts/demo/signin.js"></script>

</body>