<?php 
require("includes/init.php"); 
$page_id = basename(__FILE__);
$activeMenuItem = "Kit";				
require(INCLUDES . "/acl_module.php");
require_once(CLASSES . "/class_kit.php"); 
require_once(CLASSES . "/class_club.php"); 
 
if(!isset($_GET["id"]) && !isset($_GET['club_id'])) {
	$session->setAlertMessage("Can not edit - the ID is invalid. Please try again.");
	$session->setAlertColor("yellow");
	header("location:" . BASE_URL . "/index.php");
	exit;
}

// load the kit		
$kit_id = $_GET["id"];
$kit = new Kit();
$club = new Club();

if ($_GET["id"] ==0){
	// Change this to pass a parent value if creating a new record:
	$kit->set_club_id($_GET['club_id']);
} else {
	$kit->get_by_id($kit_id);
}

// Get club specific values:
if ($kit->get_club_id() > 0){
	$club->get_by_id($kit->get_club_id());
}
 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
	<?php  include(HEAD);  ?>
    <meta name="description" content="">

    <title><?php   echo $appConfig["app_title"];  ?> | Kit Edit</title>
	<style>
#preview_window{
  width: 100%;
  height: 400px;
  border: 2px solid #222;
  background: url('/images/mockup_preview.gif') #ccc;
  background-repeat: no-repeat;
  background-position: center;
}
.imagePreview{
cursor:pointer;
  background: #fff;
  padding: 5px;
}
.imagePreview:hover{
	text-decoration:none;
}
.carousel-caption { 
color:#222;
}
</style>	
  </head>

  <body>

<?php  require(INCLUDES . "navbar.php");  ?>
<div class="main">

    <div class="container">
      <div class="row">
        <div class="col-md-12">
        <?php  include(INCLUDES . "system_messaging.php");  ?>

        <h1><?php if ($_GET["id"] ==0){ ?> Add Kit<?php  } else { ?> Edit Kit<?php  } ?></h1>
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
	<form id="form_kit" action="<?php  echo ACTIONS_URL; ?>action_kit_edit.php" method="post">
	<input type="hidden" name="id" value="<?php  echo $kit->get_id();  ?>" />
	<input type="hidden" name="action" value="edit" />	
	<input type="hidden" name="page_id" value="<?php  echo $page_id  ?>" />	
	
         <table class="admin_table">
		 	<tr><th colspan="2">Kit Details</th></tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Club: </td>
				
					<td>
					<?php 
						$dd = new DropDown();
						$dd->set_table("club");	
						$dd->set_name_field("club_name");
						$dd->set_class_name("form-control inline");
						$dd->set_order("ASC");						
						$dd->set_name("club_id");						
						$dd->set_selected_value($kit->get_club_id());
						$dd->set_required("true");
						$dd->display();
					 ?>											
					</td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Title: </td>
            		<td><input id="title" name="title" class="form-control inline" type="text"  value="<?php  echo $kit->get_title();  ?>"   required /></td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Note: </td>
            		<td><textarea id="note" name="note" class="form-control inline" rows="4"><?php  echo $kit->get_note();  ?></textarea></td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Active: </td>

					<td>
					<?php 					
						$dd = new DropDown();
						$dd->set_static(true);	
						$dd->set_name("is_active");
						$dd->set_class_name("form-control inline");
						$dd->set_option_list("Y,N");						
						$dd->set_selected_value($kit->get_is_active());
	
						$dd->display();
					 ?>											
					</td>
				</tr>
  		
		</table>
          <br />
          <input type="submit" class="btn btn-success" value="<?php if ($_GET["id"] ==0){ ?> Add <?php  } else { ?> Save <?php  } ?>" />
          <?php  if ($kit_id > 0){  ?>
		  <a href="<?php  echo ACTIONS_URL  ?>action_kit_edit.php?action=delete&page_id=<?php  echo $page_id  ?>&id=<?php  echo $kit_id  ?>" onClick="return confirm('You are about to delete this item. Do you want to continue?');" class="btn btn-warning" role="button">Delete</a>
		   <?php  }  ?> 			   		  
          <input type="button" class="btn btn-default" value="Back" onClick="window.location ='<?php echo $_SERVER["HTTP_REFERER"];?>'" />
        </form>
		<br>
		
        <?php  if($kit->get_id() > 0){  ?>
          <p><em>Last updated: <?php  echo $kit->get_last_updated();  ?> by <?php  echo $kit->get_last_updated_user();  ?></em></p>
        <?php  }  ?>			
	
      </div>
	<div class="col-md-6">
		<?php if ($kit->get_id() > 0): ?>
		<table class="admin_table" id="kits">
			<thead>
			<tr><th colspan="6">Kit Items:<i class="fa fa-plus-circle fa-lg add-icon add-item"></i></th></tr>
			<tr><th>Item:</th><th>Price</th><th></th></tr>	
			</thead>
			
			<tbody id="kit_items_table">
		 <?php 
		 	$dm = new DataManager(); 
			$strSQL = "SELECT *, kitItem.id AS kitItemId from kitItem 
			LEFT JOIN item ON kitItem.item_id = item.item_id 
			WHERE kitItem.is_active = 'Y' AND kitItem.build_id = ". $kit->get_id();						

			$result = $dm->queryRecords($strSQL);	
			if ($result):
				while($row = mysqli_fetch_assoc($result)):
					echo '<tr><td data-item-id="' . $row['kitItemId'] . '" class="item_view_link">' . $row['item_name'] . '</td><td>$' . number_format($row['item_price'],2) . '</td><td><a href="actions/action_kitItem_edit.php?action=delete&page_id=kitItem_edit.php&id=' . $row['kitItemId'] . '" onclick="return confirm(\'You are about to delete an item. Do you want to continue?\');"><i class="fa fa-times-circle fa-lg"></i></a></td></tr>';
				endwhile;									
			endif;
		 ?>		
			</tbody>
		</table>
			
		<?php endif ?>
	</div>
    </div> 

</div><!-- /container -->
</div>

<?php  include(INCLUDES . "/footer.php");  ?>
<?php  include(INCLUDES_LIST);  ?>	
<?php  include(SCRIPTS . "kit_item_add_dialog.php"); ?>  
<?php  include(SCRIPTS . "item_preview_dialog.php"); ?>  

  </body>
</html>