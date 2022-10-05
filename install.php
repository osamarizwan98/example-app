<?php

// Set variables for our request
$shop = $_GET['shop'];
$api_key = "72aa16a76de982da993b98d66a1747c1";
$scopes = "read_orders,write_products,read_themes,write_themes,write_script_tags";
$redirect_uri = "http://localhost/apps/example_app/generate_token.php";

// Build install/approval URL to redirect to
$install_url = "https://" . $shop . ".myshopify.com/admin/oauth/authorize?client_id=" . $api_key . "&scope=" . $scopes . "&redirect_uri=" . urlencode($redirect_uri);

// Redirect
header("Location: " . $install_url);
die();