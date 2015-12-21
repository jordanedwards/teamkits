<?php
$public=true;
require("../includes/init.php"); 
require(CLASSES . "class_cpc_api.php");
error_reporting(E_ALL);
ini_set('display_errors', '1');

$CPC = new CPC();
//Minimum variables in get or post: pc_origin, pc_destination, weight:

if (isset($_REQUEST['pc_origin']) && isset($_REQUEST['pc_destination']) && isset($_REQUEST['weight'])){
	$CPC->load($_REQUEST);
	echo $CPC->get_json_data();
}
?>