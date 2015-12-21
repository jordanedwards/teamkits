<?php
ini_set('display_errors', 0);
session_start();

$_SESSION['data'][$_GET['varname']] = $_GET['val'];


// troubleshooting:

foreach($_SESSION['data'] as $key => $value){
$msg .= $key . " => " . $value . "
";
}

echo $msg; 
?>