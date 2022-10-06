<?php
require_once("inc/functions.php");
require_once("inc/connection.php");

$id = $_POST['id'];
$shop_url = $_POST['url'];

$sql = "SELECT * FROM example_table WHERE store_url='" . $shop_url . "' LIMIT 1";
$check = mysqli_query($con, $sql);

if(mysqli_num_rows($check) > 0) {
	$shop_row = mysqli_fetch_assoc($check);

	$token = $shop_row['access_token'];

	if($_POST['type'] == 'GET') {
		$products = shopify_call($token, $shop_url, "/admin/api/2022-10/products/" . $id . ".json", array(), 'GET');
		$products = json_decode($products['response'], JSON_PRETTY_PRINT);

		$id = $products['product']['id'];
		$title = $products['product']['title'];
		$description = $products['product']['body_html'];
		$collections = array();

		$custom_collections = shopify_call($token, $shop_url, "/admin/api/2020-07/custom_collections.json", array("product_id" => $id), 'GET');
		$custom_collections = json_decode($custom_collections['response'], JSON_PRETTY_PRINT);

		foreach ($custom_collections as $custom_collection) {
			foreach ($custom_collection as $key => $value) {
				array_push($collections, array("id" => $value['id'], "name" => $value['title']));
			}
		}

		$smart_collections = shopify_call($token, $shop_url, "/admin/api/2020-07/smart_collections.json", array("product_id" => $id), 'GET');
		$smart_collections = json_decode($smart_collections['response'], JSON_PRETTY_PRINT);

		foreach ($smart_collections as $smart_collection) {
			foreach ($smart_collection as $key => $value) {
				array_push($collections, array("id" => $value['id'], "name" => $value['title']));
			}
		}

		echo json_encode(
			array(
				"id" => $id,
				"title" => $title,
				"description" => $description,
				"collections" => $collections
			)
		);

	} else if( $_POST['type'] == 'PUT' ) {
		$productData = array();
		parse_str($_POST['product'], $productData);

		$array = array("product" => array("title" => $productData['productName'], "body_html" => $productData['productDescription']));
        
        $products = shopify_call($token, $shop_url, "/admin/api/2020-07/products/" . $id . ".json", $array, 'PUT');
	}
    
}
?>