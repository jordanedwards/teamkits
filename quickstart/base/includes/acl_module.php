<?php
/*
This is the Access Control List module

How it works:
	1. The page id is set on each page before the acl_module is called. The page id can be anything unique to the page, but we recommend using the file name itself.
	2. This module checks that there are records set:
		a) if there are,it checks that the current user has permission to view this page
		b) if there are not, and the user is an admin, the user may set the permissions (by posting to action_add_acl.php)
	3. The page_id is passed to action file from the edit form. The edit form also passes the action parameter. In the action file, it checks the permission for that page, for that action type (edit or delete), and for that user.
	
How to use:
	1. Put this at the top of the subject page:
		$page_id = basename(__FILE__);
		require(INCLUDES . "/acl_module.php");
	2. Create the database (sql included in quickstart, or use this:
	
		CREATE TABLE IF NOT EXISTS `aclpagerecords` (
	  `aclPageRecords_id` bigint(20) NOT NULL AUTO_INCREMENT,
	  `aclPageRecords_user_role` int(10) NOT NULL,
	  `aclPageRecords_page_id` varchar(200) NOT NULL,
	  `aclPageRecords_page_view` int(3) NOT NULL DEFAULT '0',
	  `aclPageRecords_page_edit` int(3) NOT NULL DEFAULT '0',
	  `aclPageRecords_page_delete` int(3) NOT NULL DEFAULT '0',
	  PRIMARY KEY (`aclPageRecords_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
	
	3. Log in as admin, then navigate to the page
	4. Set the permissions
	
	To edit the page's permissions, add this to the url: view_permissions=true

Full documentation at orchardcity.ca/quickstart
*/

$access = false;
$num_rows = 0;
$view_permissions = $_GET['view_permissions'];

if (!$page_id > 0){
	echo "<h1>No page id set. Please contact an administrator</h1>";
	exit();
}
	// Pull current user:
	require_once(CLASSES . "/class_user.php"); 
	$currentUser = new User;
	$currentUser->get_by_id($session->get_user_id());
	
	// Check that user is allowed access to this resource:
	$dm = new DataManager();
	
	switch ($_REQUEST['action']):
		case "view":
			$field = "aclPageRecords_page_view";
		break;
		case "edit":
			$field = "aclPageRecords_page_edit";	
		break;
		case "delete":
			$field = "aclPageRecords_page_delete";	
		break;
		default:
			$field = "aclPageRecords_page_view";	
	endswitch;
	
$query = "SELECT " . $field . " as permission, aclPageRecords_user_role FROM aclpagerecords WHERE aclPageRecords_page_id = '" .$page_id . "'";
$result = $dm->queryRecords($query);
if ($result){ $num_rows = mysqli_num_rows($result);}
if ($num_rows == 0){
	?>
	<h1>There are no Access Control records for this page</h1>
<?php if ($currentUser->get_role() ==1){?>
		<!-- User is an admin, allow them to set records -->
		<form action="<?php echo ACTIONS_URL ?>action_add_acl.php" method="post">
		<table border="1px solid; #000">
		<thead>
			<tr>
				<th></th><th>Super Users</th><th>Admins</th><th>Users</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>View</td><td><input type="checkbox" name="superuser[view]" value="1" checked="checked"/></td><td><input type="checkbox" name="admin[view]" value="1" checked="checked"/></td><td><input type="checkbox" name="user[view]" value="1" checked="checked"/></td>
			</tr>
			<tr>
				<td>Edit</td><td><input type="checkbox" name="superuser[edit]" value="1" checked="checked"/></td><td><input type="checkbox" name="admin[edit]" value="1" checked="checked" /></td><td><input type="checkbox" name="user[edit]" value="1" /></td>
			</tr>	
			<tr>
				<td>Delete</td><td><input type="checkbox" name="superuser[delete]" value="1" checked="checked"/></td><td><input type="checkbox" name="admin[delete]" value="1" /></td><td><input type="checkbox" name="user[delete]" value="1" /></td>
			</tr>
		</tbody>
		</table>
		<input type="hidden" name="page_id" value="<?php echo $page_id ?>" />
		<br />
		<button type="submit">Submit</button>
		</form>	
		<?php
		} else {
			// User is not an admin.
			echo "<p>Please contact an administrator for assistance</p>";
		}
	exit();
} else {
	// Records exist, just need to check that user has access:
	$line = NULL;
	while ($line = mysqli_fetch_array($result)) {
	//echo "permission: " . $line['permission'];
		if ($line['permission'] == 1 && $line['aclPageRecords_user_role'] == $currentUser->get_role()) 
		{
			$access = true;
		}
	}
		// No record for this user role, for this page, for this access type. Deny access
		if (!$access){
			echo "You do not have access to this resource.<br><a href='" . BASE_URL . "'>&raquo; Homepage</a>";
			exit;
		} 
	}

// If view_permisssions is set to true (and we've passed all the other steps), allow the editing of acl records
if ($view_permissions && $currentUser->get_role()==1){
	// look up existing permissions to populate:
	$strSQL = "SELECT * FROM aclpagerecords WHERE aclPageRecords_page_id = '" .$page_id ."'";
	$result = $dm->queryRecords($strSQL);
				
	if ($result):
		while ($line = mysqli_fetch_assoc($result)):
			$permissions[$line['aclPageRecords_user_role']]["view"] = $line['aclPageRecords_page_view'];
			$permissions[$line['aclPageRecords_user_role']]["edit"] = $line['aclPageRecords_page_edit'];
			$permissions[$line['aclPageRecords_user_role']]["delete"] = $line['aclPageRecords_page_delete'];
		endwhile;
	
	endif;
	
	function checkIfchecked($userrole, $type, $permissions){
		if ($permissions[$userrole][$type] > 0){
			return " checked='checked'";
		} 
	}
	
	// Notice below that we leave superuser as checked. This is because the superuser should have access to everything.
	?>
	<div style="background: rgba(255,255,255,0.7); margin-left:auto; margin-right:auto; text-align:center; padding:20px; position:absolute; right:0px; z-index:1">
	<form action="<?php echo ACTIONS_URL ?>action_add_acl.php" method="post">
			<table border="1px solid; #000" style="margin-left:auto; margin-right:auto; width:auto" class="admin_table">
			<thead>
				<tr>
					<th colspan="4" style="text-align:center"><h3>Edit Permissions</h4></th>
				</tr>			
				<tr>
					<th></th><th>Superusers</th><th>Admins</th><th>Users</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>View</td><td><input type="checkbox" name="superuser[view]" value="1" checked="checked"/></td><td><input type="checkbox" name="admin[view]" value="1" <?php echo checkIfchecked("2","view",$permissions); ?>/></td><td><input type="checkbox" name="user[view]" value="1" <?php echo checkIfchecked("3","view",$permissions); ?>/></td>
				</tr>
				<tr>
					<td>Edit</td><td><input type="checkbox" name="superuser[edit]" value="1" checked="checked"/></td><td><input type="checkbox" name="admin[edit]" value="1" <?php echo checkIfchecked("2","edit",$permissions); ?> /></td><td><input type="checkbox" name="user[edit]" value="1" <?php echo checkIfchecked("3","edit",$permissions); ?>/></td>
				</tr>	
				<tr>
					<td>Delete</td><td><input type="checkbox" name="superuser[delete]" value="1" checked="checked"/></td><td><input type="checkbox" name="admin[delete]" value="1" <?php echo checkIfchecked("2","delete",$permissions); ?>/></td><td><input type="checkbox" name="user[delete]" value="1" <?php echo checkIfchecked("3","delete",$permissions); ?>/></td>
				</tr>
			</tbody>
			<tfoot>
			<input type="hidden" name="page_id" value="<?php echo $page_id ?>" />
			<input type="hidden" name="update" value="true" />			
			<tr><th colspan="4" style="  text-align: center;"><button type="submit">Update</button></th></tr>
			</table>

			</form>
	</div>
	<?php 
}
?>
