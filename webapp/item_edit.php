<?php 
require("includes/init.php"); 
$page_id = basename(__FILE__);
require(INCLUDES . "/acl_module.php");
include(CLASSES . "/class_item.php"); 
 
	if(!isset($_GET["id"])) {
		$session->setAlertMessage("Can not edit - the ID is invalid. Please try again.");
		$session->setAlertColor("yellow");
		header("location:" . $base_href . "/index.php");
		exit;
	}

	// load the item		
	$item_id = $_GET["id"];
	$item = new Item();
	
	if ($_GET["id"] ==0){
		// Change this to pass a parent value if creating a new record:
		//	$item->set_customer_id($_GET['customer_id']);
	} else {
		$item->get_by_id($item_id);
	}
$activeMenuItem = "Manage";				
 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
	<?php  include(HEAD);  ?>
    <meta name="description" content="">

    <title><?php   echo $appConfig["app_title"];  ?> | Item Edit</title>
  </head>

  <body>

<?php  require(INCLUDES . "navbar.php");  ?>
<div class="main">

    <div class="container">
      <div class="row">
        <div class="col-md-12">
        <?php  include(INCLUDES . "system_messaging.php");  ?>

        <h1><?php if ($_GET["id"] ==0){ ?> Add Item<?php  } else { ?> Edit Item<?php  } ?></h1>
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
	<form id="form_item" action="<?php  echo ACTIONS_URL; ?>action_item_edit.php" method="post">
	<input type="hidden" name="item_id" value="<?php  echo $item->get_id();  ?>" />
	<input type="hidden" name="action" value="edit" />	
	<input type="hidden" name="page_id" value="<?php  echo $page_id  ?>" />	
	
         <table class="admin_table">
				<tr>
           			<td style="width:1px; white-space:nowrap;">Name: </td>
            		<td><input id="item_name" name="item_name" type="text"  value="<?php  echo $item->get_name();  ?>" style="width:90%"  class="{validate:{required:true}}" /> <span class='red'>*</span> </td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Price: </td>
					<td>$<input id="item_price" name="item_price" type="text" value="<?php  echo number_format($item->get_price(),2);  ?>"  style="width:90%" /></td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Brand: </td>
				
					<td>
					<?php 
						$dd = new DropDown();
						$dd->set_table("brand");	
						$dd->set_name_field("brand_name");
						$dd->set_class_name("form-control");
						$dd->set_order("ASC");						
						$dd->set_name("item_brand");						
						$dd->set_selected_value($item->get_brand());
						$dd->display();
					 ?>											
					
					</td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Exclusive club: </td>
				
					<td>
					<?php 
						$dd = new DropDown();
						$dd->set_table("club");	
						$dd->set_name_field("club_name");
						$dd->set_class_name("form-control");
						$dd->set_order("ASC");						
						$dd->set_name("item_club_id");						
						$dd->set_selected_value($item->get_club_id());
						$dd->display();
					 ?>											
					
					</td>
				</tr>
  		
		</table>
          <br />
          <input type="submit" class="btn btn-success" value="<?php if ($_GET["id"] ==0){ ?> Add <?php  } else { ?> Save <?php  } ?>" />&nbsp;
          <input type="submit" class="btn btn-warning"  value="Delete" name="delete"/>&nbsp;	  		  
          <input type="button" class="btn btn-default" value="Back" onClick="window.location ='<?php echo $_SERVER["HTTP_REFERER"];?>'" />
        </form>
		<br>
		
        <?php  if($item->get_id() > 0){  ?>
          <p><em>Last updated: <?php  echo $item->get_last_updated();  ?> by <?php  echo $item->get_last_updated_user();  ?></em></p>
        <?php  }  ?>			
	
      </div>  
	<div class="col-md-6">
		<?php if ($item_id > 0): ?>
			<table class="admin_table">
			<thead>
			<tr><th colspan="5">Images:<i class="fa fa-plus-circle fa-lg add-icon add-image"></i></th></tr>
			<tr><th></th><th>Image</th><th>Description</th><th>Active</th></tr>	
			</thead>
			
			<tbody id="images_table">
		 <?php 
		 	$dm = new DataManager(); 
			$strSQL = "SELECT * from itemImage 
			WHERE itemImage_item_id =" . $item->get_id();						

			$result = $dm->queryRecords($strSQL);	
			if ($result):
				while($row = mysqli_fetch_assoc($result)):
					echo '<tr><td><a href="itemImage_edit.php?id=' . $row['itemImage_id'] .'"><i class="fa fa-edit fa-lg"></i></a></td><td><img src="images/itemimages/' . $row['itemImage_url'] . '" class="thumbnail"></td><td>' . $row['itemImage_description'] . '</td><td style="white-space:normal">'.$row['is_active'] .'</td></tr>';
				endwhile;									
			endif;
		 ?>		
			</tbody>
		</table>
		<br>
		<table class="admin_table">
			<thead>
			<tr><th colspan="5">Sizes:<i class="fa fa-plus-circle fa-lg add-icon add-size"></i></th></tr>
			<tr><th></th><th>Size</th><th>Stock</th><th>Delete</th></tr>	
			</thead>
			
			<tbody id="sizes_table">
		 <?php 
		 	$dm = new DataManager(); 
			$strSQL = "SELECT * from itemSize 
			WHERE itemSize_item_id =" . $item->get_id() . " AND is_active ='Y'";						

			$result = $dm->queryRecords($strSQL);	
			if ($result):
				while($row = mysqli_fetch_assoc($result)):
					echo '<tr><td><a href="itemSize_edit.php?id=' . $row['itemSize_id'] .'"><i class="fa fa-edit fa-lg"></i></a></td><td>' . $row['itemSize_name'] . '</td><td>' . $row['itemSize_stock'] . '</td><td style="white-space:normal"><a href="actions/action_itemSize_edit.php?action=delete&page_id=item_edit.php&itemSize_item_id=' . $row['itemSize_item_id']. '&itemSize_id=' . $row['itemSize_id'] .'" onclick="return confirm(\'You are about to delete a size. Do you want to continue?\');"><i class="fa fa-times-circle fa-lg"></i></a></td></tr>';
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

<script type="text/javascript">
		$(document).ready(function() {
			var container = $("div.errorContainer");
			// validate the form when it is submitted
			var validator = $("#form_customers").validate({
				errorContainer: container,
				errorLabelContainer: $("ol", container),
				wrapper: "li",
				meta: "validate"
			});
	 	});

		$.validator.setDefaults({
			submitHandler: function() { form.submit();  }
		});

// Include any masks here:
		 //   $("#student_tel").mask("(999) 999-9999");
		
  </script>	
<?php include(SCRIPTS . "item_image_add_dialog.php"); ?>  
<?php include(SCRIPTS . "item_size_add_dialog.php"); ?>    	
 	
  </body>
</html>