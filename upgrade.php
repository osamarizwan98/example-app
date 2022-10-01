<?php
require_once("inc/functions.php");
require_once("inc/connection.php");

$requests = $_GET;
$hmac = $_GET['hmac'];
$serializeArray = serialize($requests);
$requests = array_diff_key($requests, array('hmac' => ''));
ksort($requests);

$sql = "SELECT * FROM `example_table` WHERE store_url ='".$requests["shop"]."' LIMIT 1 ";
$result = mysqli_query($con, $sql);
$row    = mysqli_fetch_assoc($result);

$token = $row['access_token'];

$shop = 'osama-cloth';

$array = array(
	'recurring_application_charge' => array(
		'name' => 'Example Plan',
		'test' => true,  //remove this line before sending to app store
		'price' => 15.0,
        "trial_days" => 14,
		'return_url' => 'https://osama-cloth.myshopify.com/admin/apps/example_app/'
	)
);

$charge = shopify_call($token, $shop, "/admin/api/2022-10/recurring_application_charges.json", $array, 'POST');
$charge = json_decode($charge['response'], JSON_PRETTY_PRINT);


header('Location: ' . $charge['recurring_application_charge']['confirmation_url']);
exit();