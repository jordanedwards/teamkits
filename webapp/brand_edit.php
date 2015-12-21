<?php 
require("includes/init.php"); 
$page_id = basename(__FILE__);
require(INCLUDES . "/acl_module.php");
include(CLASSES . "/class_brand.php"); 
 
	if(!isset($_GET["id"])) {
		$session->setAlertMessage("Can not edit - the ID is invalid. Please try again.");
		$session->setAlertColor("yellow");
		header("location:" . $base_href . "/index.php");
		exit;
	}

	// load the brand		
	$brand_id = $_GET["id"];
	$brand = new Brand();
	
	if ($_GET["id"] ==0){
		// Change this to pass a parent value if creating a new record:
		//	$brand->set_customer_id($_GET['customer_id']);
		$brand->set_active("Y");
	} else {
		$brand->get_by_id($brand_id);
	}
$activeMenuItem = "Manage";				
 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
	<?php  include(HEAD);  ?>
    <meta name="description" content="">

    <title><?php   echo $appConfig["app_title"];  ?> | Brand Edit</title>
  </head>

  <body>

<?php  require(INCLUDES . "navbar.php");  ?>
<div class="main">

    <div class="container">
      <div class="row">
        <div class="col-md-12">
        <?php  include(INCLUDES . "system_messaging.php");  ?>

        <h1><?php if ($_GET["id"] ==0){ ?> Add Brand<?php  } else { ?> Edit Brand<?php  } ?></h1>
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
	<form id="form_brand" enctype="multipart/form-data" action="<?php  echo ACTIONS_URL; ?>action_brand_edit.php" method="post">
	<input type="hidden" name="id" value="<?php  echo $brand->get_id();  ?>" />
	<input type="hidden" name="action" value="edit" />	
	<input type="hidden" name="page_id" value="<?php  echo $page_id  ?>" />	
	
         <table class="admin_table">
				<tr>
           			<td style="width:1px; white-space:nowrap;">Name: </td>
            		<td><input id="name" name="name" type="text"  value="<?php  echo $brand->get_name();  ?>" style="width:90%" /> </td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Currency:</td>
					<td>
					<?php 
						$dd = new DropDown();
						$dd->set_name("currency");						
						$dd->set_table("currency");	
						$dd->set_name_field("name");
						$dd->set_class_name("form-control inline");
						$dd->set_order("ASC");						
						$dd->set_selected_value($brand->get_currency());
						$dd->display();
					 ?>											
					</td>
				</tr>				
				<tr>
           			<td style="width:1px; white-space:nowrap;">Description: </td>
            		<td><textarea id="description" name="description" rows="20" style="width:90%" /><?php  echo $brand->get_description();  ?></textarea></td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Logo: </td>
            		<td>
					<input type="file" name="logo" size="30" placeholder="Upload new logo">
					<p class="small">Logos must be 176 x 176, with logo centered.</p>
					</td>
 				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Main image: </td>
            		<td>
					<input type="file" name="main_image" size="30" placeholder="Upload new main image">
					<p class="small">Maximum size: 800px wide x 500px tall.</p>
					</td>
 				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Show on public site: </td>
					<td>
					<?php 					
						$dd = new DropDown();
						$dd->set_static(true);	
						$dd->set_name("feature");
						$dd->set_class_name("form-control");
						$dd->set_option_list("Y,N");						
						$dd->set_selected_value($brand->get_feature());
						$dd->display();
					 ?>						
					</td>
				</tr>											
				<tr>
           			<td style="width:1px; white-space:nowrap;">Active: </td>
					<td>
					<?php 					
						$dd = new DropDown();
						$dd->set_static(true);	
						$dd->set_name("active");
						$dd->set_class_name("form-control");
						$dd->set_option_list("Y,N");						
						$dd->set_selected_value($brand->get_active());
						$dd->display();
					 ?>						
					</td>
				</tr>
  		
		</table>
          <br />
          <br />
          <input type="submit" class="btn btn-success" value="<?php if ($_GET["id"] ==0){ ?> Add <?php  } else { ?> Save <?php  } ?>" />&nbsp;
          <input type="submit" class="btn btn-warning"  value="Delete" name="delete"/>&nbsp;	  		  
          <input type="button" class="btn btn-default" value="Back" onClick="window.location ='<?php echo $_SERVER["HTTP_REFERER"];?>'" />
        </form>
		<br>
		
        <?php  if($brand->get_id() > 0){  ?>
          <p><em>Last updated: <?php  echo $brand->get_last_updated();  ?> by <?php  echo $brand->get_last_updated_user();  ?></em></p>
        <?php  }  ?>			
	
      </div>
	<div class="col-md-6">
	<?php if ($brand->get_id() > 0): ?>
			<table class="admin_table">
			<thead>
			<tr><th colspan="6">Catalogues:<a href="brandcatalogue_edit.php?brand_id=<?php echo $brand->get_id(); ?>"><i class="fa fa-plus-circle fa-lg add-icon"></i></a></th></tr>
			<tr><th></th><th>Name</th><th>Active</th></tr>	
			</thead>
			
			<tbody id="order_items_table">
		 <?php 
		 	$dm = new DataManager(); 
			$strSQL = "SELECT * from brandcatalogue 
			WHERE brand_id=" . $brand->get_id() . "
			AND is_active = 'Y'";						

			$result = $dm->queryRecords($strSQL);	
			if ($result):
				while($row = mysqli_fetch_assoc($result)):
					echo '<tr><td><a href="brandcatalogue_edit.php?id=' . $row['id'] .'"><i class="fa fa-edit fa-lg"></i></a></td><td>' . $row['title'] . '</a></td><td>' . $row['is_active'] . '</td></tr>';
				endwhile;									
			endif;
		 ?>		
			</tbody>
			
		</table> 
		<br>
		
		<h4>Logo:</h4>
		<?php if ($brand->get_logo() != ""){ ?>
			<img src="/img/brands/<?php echo $brand->get_logo(); ?>" class="img-responsive" style="max-width: 100%; margin-left: auto; margin-right: auto;">
		<?php }else{?>
			No logo uploaded yet.
		<?php } ?>
		<br><br>
		
		<h4>Main image:</h4>
		<?php if ($brand->get_main_image() != ""){ ?>
			<img src="/img/brands/<?php echo strtolower($brand->get_name()) . "/" . $brand->get_main_image(); ?>"  class="img-responsive" style="max-width: 100%; margin-left: auto; margin-right: auto;">
		<?php }else{?>
			No main image uploaded yet.
		<?php } ?>
		<br>
				
	<?php endif; ?>
	</div>
    </div> 

</div><!-- /container -->
</div>

<?php  include(INCLUDES . "/footer.php");  ?>
<?php  include(INCLUDES_LIST);  ?>	
		
  </body>
</html>