<?php
require_once("inc/functions.php");
require_once("inc/connection.php");

$id = $_POST['id'];
$shop_url = $_POST['url'];

$sql = "SELECT * FROM example_table WHERE store_url='" . $shop_url . "' LIMIT 1";
$check = mysqli_query($con, $sql);


function proper_parse_str($str) {
	# result array
	$arr = array();
  
	# split on outer delimiter
	$pairs = explode('&', $str);
  
	# loop through each pair
	foreach ($pairs as $i) {
	  # split into name and value
	  list($name,$value) = explode('=', $i, 2);
  
	  # if name already exists
	  if( isset($arr[$name]) ) {
		# stick multiple values into an array
		if( is_array($arr[$name]) ) {
		  $arr[$name][] = $value;
		}
		else {
		  $arr[$name] = array($arr[$name], $value);
		}
	  }
	  # otherwise, simply stick it in a scalar
	  else {
		$arr[$name] = $value;
	  }
	}
  
	# return result array
	return $arr;
  }


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
		$productData = proper_parse_str($_POST['product']);

		$array = array("product" => array("title" => urldecode($productData['productName']), "body_html" => urldecode($productData['productDescription'])));
		
		$products = shopify_call($token, $shop_url, "/admin/api/2020-07/products/" . $id . ".json", $array, 'PUT');

		$collects = shopify_call($token, $shop_url, "/admin/api/2020-07/collects.json", array('product_id' => $id), 'GET');
		$collects = json_decode($collects['response'], JSON_PRETTY_PRINT);

		foreach ($collects as $collect) {
			foreach ($collect as $key => $value) {
				$collects = shopify_call($token, $shop_url, "/admin/api/2020-07/collects/" . $value['id'] .".json", array(), 'DELETE');
			}
		}

        // print_r($productData['productCollection']);

		if(!empty($productData['productCollection']) && count((array)$productData['productCollection'] ) > 0) {
			for ($i = 0; $i < count((array)$productData['productCollection']); $i++) { 
				if(count((array)$productData['productCollection']) >= 0 && count((array)$productData['productCollection']) <= 1) {
					$value = $productData['productCollection'];
				} else {
					$value = $productData['productCollection'][$i];
				}
				

				$collects = shopify_call($token, $shop_url, "/admin/api/2020-07/collects.json", array('collect' => array('product_id' => $id, 'collection_id' => $value)), 'POST');
			}
		}
	}
	
}
?>