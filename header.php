<?php
$requests = $_GET;
$hmac = $_GET['hmac'];
$serializeArray = serialize($requests);
$requests = array_diff_key($requests, array('hmac' => ''));
ksort($requests);

$sql = "SELECT * FROM `example_table` WHERE store_url ='".$requests["shop"]."' LIMIT 1 ";
$result = mysqli_query($con, $sql);
if (mysqli_num_rows($result) < 1) {
   header('location: install.php?shop='.$requests["shop"]);
   exit();
}
else{
    $row    = mysqli_fetch_assoc($result);
    $token = $row['access_token'];
    $shop_url = $requests["shop"];
    // $shop_url = 'osama-cloth';
}