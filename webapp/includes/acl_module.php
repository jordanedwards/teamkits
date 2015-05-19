<?php
$access = false;
$num_rows = 0;

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
	//echo $query;
	$result = $dm->queryRecords($query);
	if ($result){ $num_rows = mysqli_num_rows($result);}
	if ($num_rows == 0){
	?>
	<h1>There are no Access Control records for this page</h1>
	<?php if ($currentUser->get_role() ==1){?>
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
			$session->setAlertMessage("You do not have access to that resource.");
			$session->setAlertColor("red");	
			echo "You do not have access to this resource.";
			exit;
		} 
	}
?>