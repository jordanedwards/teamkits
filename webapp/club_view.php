<?php 
require("includes/init.php"); 
$page_id = basename(__FILE__);
require(INCLUDES . "/acl_module.php");
include(CLASSES . "/class_club.php"); 
$club = new Club();
	 
// Look up which club is associated with this user, then create club object
$club->get_by_user_id($currentUser->get_id()); 
 
	if(!$club->get_id() > 0) {
		$session->setAlertMessage("Can not view - no club is assigned to this user. Please try again.");
		$session->setAlertColor("yellow");
		header("location:" . BASE_URL . "/index.php");
		exit;
	}

$activeMenuItem = "Account info";				
 ?>
<!DOCTYPE html>
<html lang="en">
  <head>
	<?php  include(HEAD);  ?>
    <title><?php   echo $appConfig["app_title"];  ?> | Club Edit</title>
  </head>
  <body>

<?php  require(INCLUDES . "navbar_club.php");  ?>
<div class="main">

    <div class="container">
      <div class="row">
        <div class="col-md-12">
        <?php  include(INCLUDES . "system_messaging.php");  ?>

        <h1>View/Edit Club Settings</h1>
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
	<form id="form_club" action="<?php  echo ACTIONS_URL; ?>action_club_edit.php" method="post">
	<input type="hidden" name="club_id" value="<?php  echo $club->get_id();  ?>" />
	<input type="hidden" name="action" value="edit" />	
	<input type="hidden" name="page_id" value="<?php  echo $page_id  ?>" />	
	
         <table class="admin_table">
		 <tr><th colspan="2">Contact Info:</th></tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Name: </td>
            		<td><input id="club_name" name="club_name" type="text"  value="<?php  echo $club->get_name();  ?>" class="form-control inline" required/></td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Email: </td>
            		<td><input id="club_email" name="club_email" type="text"  value="<?php  echo $club->get_email();  ?>" class="form-control inline" /> </td>
				</tr>				
				<tr>
           			<td style="width:1px; white-space:nowrap;">Tel: </td>
					<td><input id="club_tel" name="club_tel" type="tel" value="<?php  echo $club->get_tel();  ?>"  class="form-control inline" /></td>

				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Address: </td>
            		<td><input id="club_address" name="club_address" type="text"  value="<?php  echo $club->get_address();  ?>" class="form-control inline" /> </td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">City: </td>
            		<td><input id="club_city" name="club_city" type="text"  value="<?php  echo $club->get_city();  ?>" class="form-control inline" /> </td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Province: </td>
				
					<td>
					<?php 
						$dd = new DropDown();
						$dd->set_table("province");	
						$dd->set_name_field("province_name");
						$dd->set_class_name("form-control");
						$dd->set_order("ASC");						
						
						$dd->set_name("club_province");						
						$dd->set_selected_value($club->get_province());
						$dd->display();
					 ?>											
					
					</td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Country: </td>
				
					<td>
					<?php 
						$dd = new DropDown();
						$dd->set_table("country");	
						$dd->set_name_field("country_name");
						$dd->set_class_name("form-control");
						$dd->set_order("ASC");						
						
						$dd->set_name("club_country");						
						$dd->set_selected_value($club->get_country());
						$dd->display();
					 ?>											
					
					</td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Postal code: </td>
            		<td><input id="club_postal_code" name="club_postal_code" type="text"  value="<?php  echo $club->get_postal_code();  ?>" class="form-control inline" /> </td>
				</tr>
				</table>
				<br>
				<table class="admin_table">
				<tr><th colspan="2">Account Settings:</th></tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Sport: </td>
				
					<td>
					<?php 
						$dd = new DropDown();
						$dd->set_table("sport");	
						$dd->set_name_field("sport_name");
						$dd->set_class_name("form-control");
						$dd->set_order("ASC");
						$dd->set_name("club_sport");						
						$dd->set_selected_value($club->get_sport());
						$dd->set_disabled("true");
						$dd->display();
					 ?>											
					
					</td>
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
						$dd->set_disabled("true");
						$dd->set_name("club_brand");						
						$dd->set_selected_value($club->get_brand());
						$dd->display();
					 ?>											
					
					</td>
				</tr>				
				<tr>
           			<td style="width:1px; white-space:nowrap;">Tax: </td>
					<td>
					<?php 
						$dd = new DropDown();
						$dd->set_table("tax");	
						$dd->set_name_field("tax_title");
						$dd->set_class_name("form-control");
						$dd->set_order("ASC");
						$dd->set_disabled("true");						
						$dd->set_name("club_tax_id");						
						$dd->set_selected_value($club->get_tax_id());
						$dd->display();
					 ?>											
					
					</td>
				</tr>				

				<tr>
           			<td style="width:1px; white-space:nowrap;">Club Code: </td>
            		<td><input id="club_code" name="club_code" type="text"  value="<?php  echo $club->get_code();  ?>" class="form-control inline" disabled="disabled"/> </td>
				</tr>
				<tr>
           			<td style="width:1px; white-space:nowrap;">Account type: </td>
					<td>
					<?php 
						$dd = new DropDown();
						$dd->set_table("accounttype");	
						$dd->set_name_field("accounttype_title");
						$dd->set_class_name("form-control");
						$dd->set_order("ASC");
						$dd->set_name("club_account_type");	
						$dd->set_disabled("true");											
						$dd->set_selected_value($club->get_account_type());
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
						$dd->set_name("is_active");
						$dd->set_class_name("form-control");
						$dd->set_option_list("Y,N");						
						$dd->set_selected_value($club->get_active());
						$dd->set_disabled("true");						
						$dd->display();
					 ?>						
					</td>
				</tr>
  		
		</table>
		<br>
		<table class="admin_table">
			<tr><th colspan="2">Online access:</th></tr>
			<tr>
				<td style="width:1px; white-space:nowrap;">Login: </td>
				<td><input id="user_login" name="user_login" type="text"  value="<?php echo $club->get_user_login() ?>" class="form-control inline" /> </td>
			</tr>			
			<tr>
           		<td style="width:1px; white-space:nowrap;">Password: </td>
            	<td><input id="user_password" name="user_password" type="password"  value="" placeholder="Enter new password to update" class="form-control inline" /> </td>
			</tr>
			<tr>
           		<td style="width:1px; white-space:nowrap;">Enabled: </td>
            	<td>
				<?php 					
					$dd = new DropDown();
					$dd->set_static(true);	
					$dd->set_name("user_active");
					$dd->set_class_name("form-control");
					$dd->set_option_list("Y,N");						
					$dd->set_selected_value($club->get_user_active());
					$dd->set_disabled("true");										
					$dd->display();
				?>	
				</td>
			</tr>						 
		 </table>
		 
          <br />
          <input type="submit" class="btn-success" value="Save" />&nbsp;
          <input type="button" class="btn-default" value="Cancel" onClick="window.location ='<?php echo $_SERVER["HTTP_REFERER"];?>'" />
        </form>
		<br>
			
	
    </div>
	<div class="col-md-6">

			<table class="admin_table">
			<thead>
			<tr><th colspan="5">Orders:<a href="orders_edit.php?club_id=<?php  echo $club_id ?>"><i class="fa fa-plus-circle fa-lg add-icon"></i></a></th></tr>
			<tr><th></th><th>Date:</th><th>Status</th></tr>	
			</thead>
			
			<tbody>
		 <?php 
		 	$dm = new DataManager(); 
			$strSQL = "SELECT * from orders 
			LEFT JOIN orderstatus ON orders.order_status = orderstatus.orderstatus_id 
			WHERE order_club_id=" . $club->get_id() . "
			AND orders.is_active = 'Y'";						

			$result = $dm->queryRecords($strSQL);	
			if ($result):
				while($row = mysqli_fetch_assoc($result)):
					echo '<tr><td><a href="orders_edit.php?id=' . $row['order_id'] .'"><i class="fa fa-edit fa-lg"></i></a></td><td>' . $row['order_date_created'] . '</a></td><td>' . $row['orderstatus_title'] . '</td></tr>';
				endwhile;									
			endif;
		 ?>		
			</tbody>
		</table>

	</div>
    </div> 

</div><!-- /container -->
</div>
<?php //echo $club ?>
<?php  include(INCLUDES . "/footer.php");  ?>
<?php require(INCLUDES_LIST);?>	 

<script type="text/javascript">
// Include any masks here:
$(function() {
		 $("#club_tel").mask("(999) 999-9999");
});
  </script>
  </body>
</html>