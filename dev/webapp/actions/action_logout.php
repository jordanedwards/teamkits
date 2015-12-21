<?php
	// include necessary libraries
	require_once( "../includes/init.php");
	
	// logout the user and forward them to the login page
	$session->logout();
	$session->setAlertMessage("You have successfully logged out of the system.");
	$session->setAlertColor("green");	
	header("location: ". BASE_URL);
	exit;
?>