<?php 
$public = true;
require("webapp/includes/init.php"); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <?php  include(HEAD);  ?>
    <title>Page not found | <?php echo $appConfig["app_title"]; ?></title>

</head>
<body>

<?php require(INCLUDES . "navbar.php");?>

<div class="main">
    <div class="container" style="margin-top: 40px;">
		<div class="row">
			<div class="row">
			<div class="col-md-12">	  
			<?php if (isset($_POST['reportlink'])){
			echo "<div id='alert_message' class='green'>Thank you, the broken link has been reported!</div>";
			if($_POST['referrer'] != ""){
				addToLog("broken link on page: " . $_POST['referrer']);
			} 
			}
			?>
			</div>
		</div>
			
			<div class="col-md-12">
				<div class="widget widget-table action-table">
						<h1>Page not found</h1>
					<h4>It appears we have a broken link or a mis-typed address. Click on the button below to automatically report a broken link</h4>
					<form method="post" target="_self">
					<input type="hidden" name="referrer" value="<?php echo $_SERVER['HTTP_REFERER'] ?>">
					<button name="reportlink" type="submit">Report broken link</button>
				  </form>
				</div>
			</div>
			
							
		</div>
	</div>
</div> 
    
<?php require(INCLUDES . "footer.php"); ?>
<?php require(INCLUDES_LIST);?>

</body>
</html>