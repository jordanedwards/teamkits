<dynamic>
require("<?php echo $this->settings['includes_url']?>/init.php"); 
$page_id = basename(__FILE__);
$activeMenuItem = "<?php echo ucfirst($this->selected_table) ?>";
require(INCLUDES . "/acl_module.php");
include(CLASSES . "/class_<?php echo $this->selected_table ?>.php");

if(!isset($_GET["id"])) {
	$session->setAlertMessage("Can not edit - the ID is invalid. Please try again.");
	$session->setAlertColor("yellow");
	header("location:" . BASE_FOLDER . "/index.php");
	exit;
}

// load the <?php echo $this->selected_table ?>		
$<?php echo $this->selected_table ?>_id = $_GET["id"];
$<?php echo $this->selected_table ?> = new <?php echo ucfirst($this->selected_table) ?>();

if ($_GET["id"] ==0){
	// Change this to pass a parent value if creating a new record:
	//	$<?php echo $this->selected_table ?>->set_customer_id($_GET['customer_id']);
} else {
	$<?php echo $this->selected_table ?>->get_by_id($<?php echo $this->selected_table ?>_id);
}
				
</dynamic>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">
    <title><dynamic>  echo $appConfig["app_title"]; </dynamic> | <?php echo ucfirst($this->selected_table) ?> Edit</title>

    <link href="./bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="./css/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <link href="./css/font-awesome.css" rel="stylesheet" type="text/css">        
    <link href="./css/styles.css" rel="stylesheet">

  </head>

  <body>

<dynamic> include(INCLUDES ."nav_bar.php")</dynamic>

    <div class="container">
      <div class="row">
        <div class="col-md-12">
        <dynamic> include(INCLUDES . "system_messaging.php"); </dynamic>

        <h1><dynamic>if ($_GET["id"] ==0){</dynamic> Add <?php echo ucfirst($this->selected_table) ?><dynamic> } else {</dynamic> Edit <?php echo ucfirst($this->selected_table) ?><dynamic> }</dynamic></h1>
        <p><span class="red">*</span> The red asterisk indicates all mandatory fields.</p>
        <div class="errorContainer">
          <p><strong>There are errors in your form submission. Please read below for details.</strong></p>
          <ol>
		  <?php
foreach($field_names as $key => $val){
?>
            <li><label for="<?php echo $key?>" class="error">Please enter the <?php echo ucfirst($val)?></label></li>
<?php
}
?>
          </ol>
		  <br>
        </div>

	<form id="form_<?php echo $this->selected_table ?>" action="<dynamic> echo ACTIONS_URL;</dynamic>action_<?php echo $this->selected_table ?>_edit.php" method="post">
	<input type="hidden" name="<?php echo $this->selected_table ?>_id" value="<dynamic> echo $<?php echo $this->selected_table ?>->get_id(); </dynamic>" />
	<input type="hidden" name="action" value="edit" />	
	<input type="hidden" name="page_id" value="<dynamic> echo $page_id </dynamic>" />	
	
         <table class="admin_table">
<?php
foreach($this->field_names as $key => $val):
$required_text = "";
$required = false;


if ($this->required_field_names[$key] == 1){
	$required_text = ' class="required" ';
	$required = true;
}

?>
				<tr>
<?php
if ($this->field_types[$key] == "dropdown_dynamic" ):
// populate dropdowns
?>				
					<td>
					<dynamic>
						$dd = new DropDown();
						$dd->set_table("<?php echo $val; ?>");	
						$dd->set_name_field("<?php echo $val; ?>_name");
						$dd->set_class_name("form-control");
						$dd->set_order("ASC");						
						$dd->set_preset("supplier");
						$dd->set_name("<?php echo $key?>");						
						$dd->set_selected_value($<?php echo $this->selected_table; ?>->get_<?php echo $val; ?>());
						$dd->display();
					</dynamic>											
					
					<?php if($required){ echo "<span class='red'> *</span> ";}?></td>
<?php
elseif ($this->field_types[$key] == "dropdown_static"):
?>

					<td>
					<dynamic>					
						$dd = new DropDown();
						$dd->set_static(true);	
						$dd->set_name("<?php echo $key?>");
						$dd->set_class_name("form-control");
						$dd->set_option_list("Y,N");						
						$dd->set_selected_value($<?php echo $this->selected_table; ?>->get_<?php echo $val; ?>());
						$dd->display();
					</dynamic>						
					<?php if($required){ echo "<span class='red'> *</span> ";}?></td>
<?php
elseif ($this->field_types[$key] == "tel"):
?>
					<td><input id="<?php echo $key?>" name="<?php echo $key?>" type="tel" value="<dynamic> echo $<?php echo $this->selected_table ?>->get_<?php echo $val?>(); </dynamic>"  style="width:90%" <?php echo $required_text ?>/><?php if($required){ echo "<span class='red'> *</span> ";}?></td>

<?php 
$scripts .='
$(document).ready(function() {
	$("#<?php echo $key?>").mask("(999) 999-9999"); 
});	
';
?>
<?php
elseif ($this->field_types[$key] == "currency"):
?>
					<td>$<input id="<?php echo $key?>" name="<?php echo $key?>" type="number" step=".01" value="<dynamic> echo $<?php echo $this->selected_table ?>->get_<?php echo $val?>(); </dynamic>"  style="width:90%" <?php echo $required_text ?>/><?php if($required){ echo "<span class='red'> *</span> ";}?></td>
<?php 
$scripts .='
$(document).ready(function() {
	$("#<?php echo $key?>").mask("999999999999.99"); 
});	
';
?>
<?php
else:
?>
            		<td><input id="<?php echo $key?>" name="<?php echo $key?>" type="<?php echo $this->field_types[$key] ?>" <?php if ($this->field_types[$key] == "number"){echo 'step="any"';} ?> value="<dynamic> echo $<?php echo $this->selected_table ?>->get_<?php echo $val?>(); </dynamic>" style="width:90%" <?php echo $required_text ?>/> <?php if($required){ echo "<span class='red'>*</span> ";}?></td>
<?php
endif;
?>				</tr>
<?php
endforeach;
?>
  		
		</table>
          <br />
          <input type="submit" value="Add/Update <?php echo ucfirst($this->selected_table) ?>" />&nbsp;&nbsp;
          <input type="button" value="Cancel" onClick="window.location ='<?php print("<?php"); ?> echo $_SERVER["HTTP_REFERER"];?>'" />
        </form>
		<br>
		
        <dynamic> if($<?php echo $this->selected_table ?>->get_id() > 0){ </dynamic>
          <p><em>Last updated: <dynamic> echo $<?php echo $this->selected_table ?>->get_last_updated(); </dynamic> by <dynamic> echo $<?php echo $this->selected_table ?>->get_last_updated_user(); </dynamic></em></p>
        <dynamic> } </dynamic>			
	
      </div>
    </div> 

</div><!-- /container -->

	<footer>
      <div class="container">
        <dynamic> include(INCLUDES . "footer.php"); </dynamic>
      </div>
    </footer>
	
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="/js/jquery.js"></script>
	<script type="text/javascript" src="/js/jquery.metadata.js"></script>
	<script type="text/javascript" src="/js/jquery.validate.js"></script>
	<script type="text/javascript" src="/js/jquery-ui-custom.js"></script>
	<script type="text/javascript" src="/js/jquery.mask.js"></script>
	
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
<script type="text/javascript">

// Validation:
$(document).ready(function() {
	var rules = {
		    	rules: {
					<?php
					foreach($this->required_field_validate as $key => $val){
						echo $key . ": {
						" . $val . "
					},";
					}
					?>
				
				}
		    };
		
	    var validationObj = $.extend (rules, Application.validationRules);
		$('#form_<?php echo $this->selected_table ?>').validate(validationObj);
});		

  </script>
<script>
<?php echo $scripts; ?>

</script>  
  </body>
</html>