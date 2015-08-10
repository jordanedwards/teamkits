<?php
$public = true;
require("init.php");
require("../stripe/stripe-php-2.3.0/lib/Stripe.php");

\Stripe\Stripe::setApiKey("sk_test_PepGB7qjKx5YGDFmVim6PjR3");
$input = @file_get_contents("php://input");
$event_json = json_decode($input, true);

//print_r($event_json);
$str = "Id: " . $event_json['id'] . "
";
$str .= "Customer: " . $event_json['data']['object']['customer'];

mail("jordan@orchardcity.ca","test",$str );
echo "success";
/*

// Set your secret key: remember to change this to your live secret key in production
// See your keys here https://dashboard.stripe.com/account/apikeys

// Retrieve the request's body and parse it as JSON


// Do something with $event_json
addToLog("stripe feed: ".$event_json);
mail("jordan@orchardcity.ca","test",$event_json);*/
http_response_code(200); // PHP 5.4 or greater
?>