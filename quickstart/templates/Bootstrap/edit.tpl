<dynamic>
require("<?php echo $this->settings['includes_url']?>/init.php"); 
$page_id = basename(__FILE__);
$activeMenuItem = "<?php echo ucfirst($this->selected_table) ?>";				
require(INCLUDES . "/acl_module.php");
require_once(CLASSES . "/class_<?php echo $this->selected_table ?>.php"); 
 
if(!isset($_GET["id"])) {
	$session->setAlertMessage("Can not edit - the ID is invalid. Please try again.");
	$session->setAlertColor("yellow");
	header("location:" . BASE_URL . "/index.php");
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
	<dynamic> include(HEAD); </dynamic>
    <meta name="description" content="">

    <title><dynamic>  echo $appConfig["app_title"]; </dynamic> | <?php echo ucfirst($this->selected_table) ?> Edit</title>
  </head>

  <body>

<dynamic> require(INCLUDES . "navbar.php"); </dynamic>
<div class="main">

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
		</div>
	</div>
	
	<div class="row">
	<div class="col-md-6">
	<form id="form_<?php echo $this->selected_table ?>" action="<dynamic> echo ACTIONS_URL;</dynamic>action_<?php echo $this->selected_table ?>_edit.php" method="post">
	<input type="hidden" name="id" value="<dynamic> echo $<?php echo $this->selected_table ?>->get_id(); </dynamic>" />
	<input type="hidden" name="action" value="edit" />	
	<input type="hidden" name="page_id" value="<dynamic> echo $page_id </dynamic>" />	
	
         <table class="admin_table">
<?php
foreach($this->field_names as $key => $val):
$required_text = "";
$required = false;

if ($this->required_field_names[$key] == 1){
	$required_text = ' class="{validate:{required:true}}" ';
	$required = true;
}

?>
				<tr>
           			<td style="width:1px; white-space:nowrap;"><?php echo str_replace("_"," ",ucfirst($val)) ?>: </td>
<?php
if ($this->field_types[$key] == "dropdown_dynamic" ):
// populate dropdowns
?>				
					<td>
					<dynamic>
						$dd = new DropDown();
						$dd->set_table("<?php echo $this->dd_tables[$key]; ?>");	
						$dd->set_name_field("<?php echo $this->dd_table_name_field[$key]; ?>");
						$dd->set_class_name("form-control inline");
						$dd->set_order("ASC");						
						$dd->set_name("<?php echo $val?>");						
						$dd->set_selected_value($<?php echo $this->selected_table; ?>->get_<?php echo $val; ?>());
<?php if($required){ echo '						$dd->set_required("true");
';}?>
						$dd->display();
					</dynamic>											
					</td>
<?php
elseif ($this->field_types[$key] == "dropdown_static"):
?>

					<td>
					<dynamic>					
						$dd = new DropDown();
						$dd->set_static(true);	
						$dd->set_name("<?php echo $val?>");
						$dd->set_class_name("form-control inline");
						$dd->set_option_list("Y,N");						
						$dd->set_selected_value($<?php echo $this->selected_table; ?>->get_<?php echo $val; ?>());
<?php if($required){ echo '						$dd->set_required("true");
';} ?>	
						$dd->display();
					</dynamic>											
					</td>
<?php
elseif ($this->field_types[$key] == "tel"):
?>
					<td><input id="<?php echo $key?>" name="<?php echo $val?>" class="form-control inline" type="tel" value="<dynamic> echo $<?php echo $this->selected_table ?>->get_<?php echo $val?>(); </dynamic>" <?php if($required){ echo " required ";}?> /></td>

<?php 
$scripts .='
$(document).ready(function() {
	$("#' . $key . '").mask("(999) 999-9999"); 
});	
';
?>
<?php
elseif ($this->field_types[$key] == "currency"):
?>
					<td>$<input id="<?php echo $key?>" name="<?php echo $val?>" class="form-control inline" type="number" step=".01" min="0" value="<dynamic> echo $<?php echo $this->selected_table ?>->get_<?php echo $val?>(); </dynamic>"  <?php if($required){ echo " required ";}?> /></td>

<?php 
$scripts .='
$(document).ready(function() {
	$("#' . $key . '").mask("999999999999.99"); 
});	
';
?>

<?php
elseif ($this->field_types[$key] == "textarea"):
?>
					<td><textarea id="<?php echo $key?>" name="<?php echo $val?>" class="form-control inline" rows="8" <?php if($required){ echo " required ";}?>><dynamic> echo $<?php echo $this->selected_table ?>->get_<?php echo $val?>(); </dynamic></textarea></td>
					
<?php
elseif ($this->field_types[$key] == "password"):
$scripts .='
<dynamic> include(SCRIPTS . "password_strength.js"); </dynamic> 	
';
?>
					<td><input id="password" name="<?php echo $val?>" class="form-control inline" <?php if($required){ echo " required ";}?> placeholder="Enter value to change password" style="width:60%">
					<span id="result" style="display:inline-block"></span>
					</td>
<?php
else:
?>
            		<td><input id="<?php echo $key?>" name="<?php echo $val?>" class="form-control inline" type="<?php echo $this->field_types[$key] ?>" <?php if ($this->field_types[$key] == "number"){echo 'step="any"';} ?> value="<dynamic> echo $<?php echo $this->selected_table ?>->get_<?php echo $val?>(); </dynamic>"  <?php if($required){ echo " required ";}?>/></td>
<?php
endif;
?>				</tr>
<?php
endforeach;
?>
  		
		</table>
          <br />
          <input type="submit" class="btn-primary" value="<dynamic>if ($_GET["id"] ==0){</dynamic> Add <dynamic> } else {</dynamic> Save <dynamic> }</dynamic>" />&nbsp;&nbsp;
          <input type="button" class="btn-default" value="Cancel" onClick="window.location ='<?php print("<?php"); ?> echo $_SERVER["HTTP_REFERER"];?>'" />
        </form>
		<br>
		
        <dynamic> if($<?php echo $this->selected_table ?>->get_id() > 0){ </dynamic>
          <p><em>Last updated: <dynamic> echo $<?php echo $this->selected_table ?>->get_last_updated(); </dynamic> by <dynamic> echo $<?php echo $this->selected_table ?>->get_last_updated_user(); </dynamic></em></p>
        <dynamic> } </dynamic>			
	
      </div>
    </div> 

</div><!-- /container -->
</div>

<dynamic> include(INCLUDES . "/footer.php"); </dynamic>
<dynamic> include(INCLUDES_LIST); </dynamic>	
<script>
// Include any masks here:
		 //   $("#student_tel").mask("(999) 999-9999");
<?php echo $scripts; ?>

</script>
  </body>
</html>