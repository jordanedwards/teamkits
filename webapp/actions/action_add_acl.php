<?php // include necessary libraries
	require_once("../includes/init.php");

	// Pull current user:
	require_once(CLASSES . "class_user.php"); 
	$currentUser = new User;
	$currentUser->get_by_id($session->get_user_id());

	if ($currentUser->get_role() == 1){
	$dm = new DataManager();
	//  User is an admin, so let er rip.

$admin_view = 0;
$admin_edit = 0;
$admin_delete = 0;
$superuser_view = 0;
$superuser_edit = 0;
$superuser_delete = 0;
$user_view = 0;
$user_edit = 0;
$user_delete = 0;

// Admin additions	
if (isset($_POST['admin'])){ 
	foreach ($_POST['admin'] as $key => $val)
	{
		if ($key == "view"){ $admin_view = $val;}
		elseif ($key == "edit"){ $admin_edit = $val;}
		elseif ($key == "delete"){ $admin_delete = $val;}
	}
if (($admin_view + $admin_edit + $admin_delete) >0 ){
		// I.e., there is a reason to put a record in:
		$query = "INSERT INTO aclpagerecords (aclPageRecords_user_role, aclPageRecords_page_id, aclPageRecords_page_view, aclPageRecords_page_edit, aclPageRecords_page_delete) VALUES
		(1,'" . mysqli_real_escape_string($dm->connection,$_POST['page_id']) . "', " . $admin_view . ", " . $admin_edit . ", " . $admin_delete . ")";
		$result = $dm->queryRecords($query);
	}
}

//superuser additions:
if (isset($_POST['superuser'])){
	foreach ($_POST['superuser'] as $key => $val)
	{
		if ($key == "view"){ $superuser_view = $val;}
		elseif ($key == "edit"){ $superuser_edit = $val;}
		elseif ($key == "delete"){ $superuser_delete = $val;}
	}
	if (($superuser_view + $superuser_edit + $superuser_delete) >0 ){
		// I.e., there is a reason to put a record in:
		$query = "INSERT INTO aclpagerecords (aclPageRecords_user_role, aclPageRecords_page_id, aclPageRecords_page_view, aclPageRecords_page_edit, aclPageRecords_page_delete) VALUES
		(2,'" . mysqli_real_escape_string($dm->connection,$_POST['page_id']) . "', " . $superuser_view . ", " . $superuser_edit . ", " . $superuser_delete . ")";
		$result = $dm->queryRecords($query);
	}
}

//user additions:
if (isset($_POST['user'])){
	foreach ($_POST['user'] as $key => $val)
	{
		if ($key == "view"){ $user_view = $val;}
		elseif ($key == "edit"){ $user_edit = $val;}
		elseif ($key == "delete"){ $user_delete = $val;}
	}
	if (($user_view + $user_edit + $user_delete) >0 ){
		// I.e., there is a reason to put a record in:
		$query = "INSERT INTO aclpagerecords (aclPageRecords_user_role, aclPageRecords_page_id, aclPageRecords_page_view, aclPageRecords_page_edit, aclPageRecords_page_delete) VALUES
		(3,'" . mysqli_real_escape_string($dm->connection,$_POST['page_id']) . "', " . $user_view . ", " . $user_edit . ", " . $user_delete . ")";
		$result = $dm->queryRecords($query);
	}
}


	$session->setAlertMessage("Access control list updated.");
	$session->setAlertColor("green");
	//rem out this line if you want to see the array variables:	
	//header("location:" . BASE_URL . "/index.php");
	header("location:" . $_SERVER['HTTP_REFERER']);	
	

echo "<pre>";
print_r($_POST);

echo "</pre>";
exit();
}
?>

