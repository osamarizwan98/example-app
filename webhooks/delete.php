<?php
require_once("../inc/connection.php");
require_once("../inc/functions.php");

define('SHOPIFY_APP_SECRET', '664c7230f2e1e83511f3519489369e85'); // Replace with your SECRET KEY

function verify_webhook($data, $hmac_header)
{
  $calculated_hmac = base64_encode(hash_hmac('sha256', $data, SHOPIFY_APP_SECRET, true));
  return hash_equals($hmac_header, $calculated_hmac);
}

$res = '';
$hmac_header = $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];
$topic_header = $_SERVER['HTTP_X_SHOPIFY_TOPIC'];
$shop_header = $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'];
$data = file_get_contents('php://input');
$utf8 = utf8_encode($data);
$decoded_data = json_decode($utf8, true);

$verified = verify_webhook($data, $hmac_header);

if( $verified ) {
$res = $decoded_data
} else {
  $res = 'The request is not from Shopify';
}

$log = fopen($shop_header. ".json", "w") or die("Unable to open file!");
fwrite($log, $res);
fclose($log);
?>