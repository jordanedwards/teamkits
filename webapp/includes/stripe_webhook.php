<?php
//mail("jordan@orchardcity.ca","test","Webhook contacted" );
$public = true;
require("init.php");
require("../stripe/stripe-php-2.3.0/lib/Stripe.php");

\Stripe\Stripe::setApiKey(STRIPE_API_KEY);

$input = @file_get_contents("php://input");
$event_json = json_decode($input, true);

//print_r($event_json);
//$str .= "Customer: " . $event_json['data']['object']['customer'];*/

// Log transaction:
addToLog("STRIPE txn:<br>".print_r($event_json,true));

http_response_code(200); // PHP 5.4 or greater
?>