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

// $collectionList = shopify_call($token, $shop, "/admin/api/2020-07/custom_collections.json", array(), 'GET');
// $collectionList = json_decode($collectionList['response'], JSON_PRETTY_PRINT);
// $collection_id = $collectionList['custom_collections'][0]['id'];
// $array = array("collection_id"=>$collection_id);
// $collects = shopify_call($token, $shop, "/admin/api/2022-07/collections/".$collection_id."/products.json", array(), 'GET');
// $collects = json_decode($collects['response'], JSON_PRETTY_PRINT);
// foreach ($collects as  $collect) {
//     foreach ($collect as $value) {
//         // $products = shopify_call($token, $shop, "/admin/api/2022-07/products/".$value['product_id'].".json", array(), 'GET');
//         // $products = json_decode($products['response'], JSON_PRETTY_PRINT);
//         echo $value['title'] .'<br>';
//     }
// }

$theme =  shopify_call($token, $shop, "/admin/api/2022-07/themes.json", array(), 'GET');
$theme = json_decode($theme['response'], JSON_PRETTY_PRINT);
// echo '<pre>';
// print_r($theme);
foreach ($theme as $currTheme) {
    foreach ($currTheme as $key => $value) {
        if($value['role'] == 'main'){
            echo 'Theme Id: '.$value['id'];
            echo '<br>Theme Name: '.$value['name'];

            $array = array(
                'asset' => array(
                    'key' => 'sections/weeklyhow.liquid',
                    "value" => "<h1>Hello World!</h1>"
                )
            );
            $assets =  shopify_call($token, $shop, "/admin/api/2022-07/themes/".$value['id']."/assets.json", $array, 'PUT');
            $assets = json_decode($assets['response'], JSON_PRETTY_PRINT);
        }
    }
}
// ?>
 <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
 <form method="post" style="margin-top: 50px">
     <label for="">Field: </label><br>
     <input type="text" name="test_field" id="test_field"><br></br>
     <input name="submit" type="submit" value="Submit">
 </form> -->
<?php 

// if(isset($_POST['submit'])){
//     echo $_POST['test_field'];
// }


// $script_array = array(
//     'script_tag' => array(
//         'event' => 'onload',
//         'src' => 'https://localhost/apps/example_app/js/script.js'
//     )
// );
// $script_tag = shopify_call($token, $shop, "/admin/api/2022-07/script_tags.json", $script_array, 'POST');
// $script_tag = json_decode($script_tag['response'], JSON_PRETTY_PRINT);

/** Recurring App Start **/
// if( isset($_GET['charge_id']) && $_GET['charge_id'] != '' ) {
// 	$charge_id = $_GET['charge_id'];

// 	$array = array(
// 		'recurring_application_charge' => array(
// 			"id" => $charge_id,
// 		    "name" => "Example Plan",
// 		    "api_client_id" => rand(1000000, 9999999),
// 		    "price" => "1.00",
// 		    "status" => "accepted",
// 		    "return_url" => "https://osama-cloth.myshopfy.com/admin/apps/example_app",
// 		    "billing_on" => null,
// 		    "test" => true,
// 		    "activated_on" => null,
// 		    "trial_ends_on" => null,
// 		    "cancelled_on" => null,
// 		    "trial_days" => 14,
// 		    "decorated_return_url" => "https://osama-cloth.myshopfy.com/admin/apps/example_app/?charge_id=" . $charge_id
// 		)
// 	);

// 	$activate = shopify_call($token, $shop, "/admin/api/2022-10/recurring_application_charges/".$charge_id."/activate.json", $array, "POST");
// 	$activate = json_decode($activate['response'], JSON_PRETTY_PRINT);
// 	print_r($activate);
	
// }
?>

<!DOCTYPE html>
<html>
<head>
	<title>Shopify Example App</title>
</head>
<body>
	<h1>Shopify App Example</h1>
	<!-- <input type="text" id="search" name="search" placeholder="Search for item">
	<input type="hidden" id="subdomain" name="subdomain" value="<?php echo $shop; ?>">
	<div id="products"></div>

	<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	<script>
		$('#search').keypress(function (e) {
		if (e.which == 13) {

		    var search = $(this).val();
		    var shop = $('#subdomain').val();

		    $.ajax({
		        type: "POST",
		        url: "search.php", 
		        data: {
		        	term: search,
		        	subdomain: shop
		        },           
		        dataType: "html",               
		        success: function(response){                    
		            $('#products').html(response);
		        }
		    });
		    return false;
		  }
	});
	</script> -->

	<!-- <p>You're still using our free trial. Click the upgrade button below to have access to more awesome features!</p>
	<a href="upgrade.php?<?php echo $_SERVER['QUERY_STRING']; ?>" target="_blank">Upgrade</a> -->

	

</body>
</html>