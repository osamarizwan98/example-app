<?php
require_once("inc/functions.php");
include_once("inc/connection.php");


// $shop_url = $_GET['shop'];
// $access_token = 'shpca_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';


$sql = "SELECT * FROM `example_table` WHERE store_url ='".$requests["shop"]."' LIMIT 1 ";
$result = mysqli_query($con, $sql);
$row    = mysqli_fetch_assoc($result);
$token = $row['access_token'];
$shop_url = $requests["shop"];


$rel = $_GET['rel'];
$page_info = $_GET['page_info'];

//Create an array for the API
$array = array(
    'limit' => 4,
    'page_info' => $page_info,
    'rel' => $rel
);

$products = rest_api($token, $shop_url, "/admin/api/2021-07/products.json", $array, 'GET');

//Get the headers
$headers = $products['headers'];

//Create an array for link header
$link_array = array();

print_r($headers);

//Check if there's more than one links / page infos. Otherwise, get the one and only link provided
if( strpos( $headers['link'], ',' )  !== false ) {
	$link_array = explode(',', $headers['link'] );
} else {
	$link = $headers['link'];
}

//Create variables for the new page infos
$prev_link = '';
$next_link = '';

//Check if the $link_array variable's size is more than one
if( sizeof( $link_array ) > 1 ) {
    $prev_link = $link_array[0];
    $prev_link = str_btwn($prev_link, '<', '>');

    $param = parse_url($prev_link);
    parse_str($param['query'], $prev_link);
    $prev_link = $prev_link['page_info'];

    $next_link = $link_array[1];
    $next_link = str_btwn($next_link, '<', '>');

    $param = parse_url($next_link);
    parse_str($param['query'], $next_link);

    $next_link = $next_link['page_info'];
} else {
    $rel = explode(";", $headers['link']);
    $rel = str_btwn($rel[1], '"', '"');

    if($rel == "previous") {
        $prev_link = $link;
        $prev_link = str_btwn($prev_link, '<', '>');

        $param = parse_url($prev_link);
        parse_str($param['query'], $prev_link);

        $prev_link = $prev_link['page_info'];

        $next_link = "";
    } else {
        $next_link = $link;
        $next_link = str_btwn($next_link, '<', '>');

        $param = parse_url($next_link);
        parse_str($param['query'], $next_link);

        $next_link = $next_link['page_info'];

        $prev_link = "";
    }
}

//Create and loop through the next or previous products
$html = '';

$products = json_decode($products['data'], true);

foreach($products as $product) {
    foreach($product as $key => $value) {
		$html .= '<li>' . $value['title'] . '</li>';
    }
}

//Then we return the values back to ajax
echo json_encode( array( 'prev' => $prev_link, 'next' => $next_link, 'html' => $html ) );