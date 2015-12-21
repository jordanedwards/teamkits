<?php 
require("includes/init.php"); 
$page_id = basename(__FILE__);
$activeMenuItem = "Currency";				
require(INCLUDES . "/acl_module.php");
require_once(CLASSES . "/class_currency.php"); 
 
if(!isset($_GET["id"])) {
	$session->setAlertMessage("Can not edit - the ID is invalid. Please try again.");
	$session->setAlertColor("yellow");
	header("location:" . BASE_URL . "/index.php");
	exit;
}

// load the currency		
$currency_id = $_GET["id"];
$currency = new Currency();

if ($_GET["id"] ==0){
	// Change this to pass a parent value if creating a new record:
	//	$currency->set_customer_id($_GET['customer_id']);
} else {
	$currency->get_by_id($currency_id);
}
 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
	<?php  include(HEAD);  ?>
    <meta name="description" content="">

    <title><?php   echo $appConfig["app_title"];  ?> | Currency Edit</title>
  </head>

  <body>

<?php  require(INCLUDES . "navbar.php");  ?>
<div class="main">

    <div class="container">
      <div class="row">
        <div class="col-md-12">
        <?php  include(INCLUDES . "system_messaging.php");  ?>

        <h1><?php if ($_GET["id"] ==0){ ?> Add Currency<?php  } else { ?> Edit Currency<?php  } ?></h1>
        <p><span class="red">*</span> The red asterisk indicates all mandatory fields.</p>
        <div class="errorContainer">
          <p><strong>There are errors in your form submission. Please read below for details.</strong></p>
          <ol>
		            </ol>
		  <br>
        </div>
		</div>
	</div>
	
	<div class="row">
	<div class="col-md-6">
	<form id="form_currency" action="<?php  echo ACTIONS_URL; ?>action_currency_edit.php" method="post">
	<input type="hidden" name="id" value="<?php  echo $currency->get_id();  ?>" />
	<input type="hidden" name="action" value="edit" />	
	<input type="hidden" name="page_id" value="<?php  echo $page_id  ?>" />	
	
         <table class="admin_table">
				<tr>
           			<td style="width:1px; white-space:nowrap;">Name: </td>
            		<td><input id="name" name="name" class="form-control inline" type="text"  value="<?php  echo $currency->get_name();  ?>"   required /></td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Shortname: </td>
            		<td><input id="shortname" name="shortname" class="form-control inline" type="text"  value="<?php  echo $currency->get_shortname();  ?>"   required /></td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Is active: </td>

					<td>
					<?php 					
						$dd = new DropDown();
						$dd->set_static(true);	
						$dd->set_name("is_active");
						$dd->set_class_name("form-control inline");
						$dd->set_option_list("Y,N");						
						$dd->set_selected_value($currency->get_is_active());
						$dd->set_required("true");
	
						$dd->display();
					 ?>											
					</td>
				</tr>
  		
		</table>
          <br />
          <input type="submit" class="btn btn-primary" value="<?php if ($currency_id ==0){ ?> Add <?php  } else { ?> Save <?php  } ?>" />
          <?php  if ($currency_id > 0){  ?>
		  <a href="<?php  echo ACTIONS_URL  ?>action_kit_edit.php?action=delete&page_id=<?php  echo $page_id  ?>&id=<?php  echo $currency_id  ?>" onClick="return confirm('You are about to delete this item. Do you want to continue?');" class="btn btn-warning" role="button">Delete</a>
		   <?php  }  ?> 		  
          <input type="button" class="btn btn-default" value="Cancel" onClick="window.location ='<?php echo $_SERVER["HTTP_REFERER"];?>'" />
        </form>
		<br>
		
        <?php  if($currency->get_id() > 0){  ?>
          <p><em>Last updated: <?php  echo $currency->get_last_updated();  ?> by <?php  echo $currency->get_last_updated_user();  ?></em></p>
        <?php  }  ?>			
	
      </div>
    </div> 

</div><!-- /container -->
</div>

<?php  include(INCLUDES . "/footer.php");  ?>
<?php  include(INCLUDES_LIST);  ?>	
<script>
// Include any masks here:
		 //   $("#student_tel").mask("(999) 999-9999");

</script>
  </body>
</html>