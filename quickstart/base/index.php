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
	<meta charset="utf-8">
    <title>Login | <?php echo $appConfig["app_title"]; ?></title>
    <?php require(HEAD) ?>
    <link href="./css/signin.css" rel="stylesheet">   
</head>

<body>

<?php require(INCLUDES . "navbar.php");?>
	
<div class="container main">
	<div class="col-md-6 col-md-offset-3">	  
	  <?php  include(INCLUDES . "system_messaging.php");  ?>
	</div>
		
	<div class="account-container">		
		<div class="content clearfix">
			<form action="actions/action_login_user.php" method="post">
			
				<h1>Login</h1>		
				
				<div class="login-fields">
					
					<p>Sign in using your registered account:</p>
					
					<div class="field">
						<label for="email">Username:</label>
							<div class="input-group">
							  <div class="input-group-addon"><i class="fa fa-user"></i></div>
							  <input type="text" class="form-control input-lg inline" id="email" name="email" placeholder="Username" required>
							</div>
						<!--<input type="email" id="email" name="email" value="" placeholder="Username" class="form-control input-lg username-field" >-->
					</div> <!-- /field -->
					
					<div class="field">
						<label for="password">Password:</label>
						<div class="input-group">
							  <div class="input-group-addon"><i class="fa fa-key"></i></div>
							  <input type="password" class="form-control input-lg inline" id="password" name="password" placeholder="Password" required>
						</div>
					</div> <!-- /password -->
					
				</div> <!-- /login-fields -->
				
				<div class="login-actions">
					
					<span class="login-checkbox">
						<input id="Field" name="Field" type="checkbox" class="field login-checkbox" value="First Choice" tabindex="4">
						<label class="choice" for="Field">Keep me signed in</label>
					</span>
										
					<button class="login-action btn btn-primary">Sign In</button>
					
				</div> 
				
			<!--	<div class="login-social">
					<p>Sign in using social network:</p>
					
					<div class="twitter">
						<a href="#" class="btn_1">Login with Twitter</a>				
					</div>
					
					<div class="fb">
						<a href="#" class="btn_2">Login with Facebook</a>				
					</div>
				</div>-->
				
			</form>			
		</div> <!-- /content -->		
	</div> <!-- /account-container -->

	<!-- Text Under Box -->
	<div class="login-extra">
		&raquo; Reset your <a href="forgot_password.php">password</a>
	</div> <!-- /login-extra -->
</div>
  
<?php require(INCLUDES . "footer.php"); ?>
<?php require(INCLUDES_LIST); ?>
<script src="./scripts/signin.js"></script>

<body>