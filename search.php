<?php
require_once("inc/functions.php");
require_once("inc/connection.php");

$html = '';
$search_term = $_POST['term'];
$shop = $_POST['subdomain'];
$token = $row['access_token'];

$array = array(
    'fields' => 'id,title'
);
$products = shopify_call($token, $shop, "/admin/api/2019-10/products.json", $array, 'GET');
$products = json_decode($products['response'], JSON_PRETTY_PRINT);

foreach ($products as $product) {
	foreach ($product as $key => $value) {
		if( stripos( $value['title'], $search_term )) {
			$html .= '<p>' . $value['title'] . '</p>';
		}
	}
}

echo $html;