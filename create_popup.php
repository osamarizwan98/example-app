<?php
$theme = shopify_call($token, $shop_url, "/admin/api/2022-10/themes.json", array(), 'GET');
$theme = json_decode($theme['response'], JSON_PRETTY_PRINT);




foreach ($theme as $current_theme) {
    foreach ($current_theme as $value) {
        if($value['role'] == 'main'){
            $theme_id = $value['id']; 

            $array = array(
                'asset' => array(
                    'key' => 'layout/theme.liquid'
                )
            );
            $assets = shopify_call($token, $shop_url, "/admin/api/2022-10/themes/".$theme_id."/assets.json", $array, 'GET');
            $assets = json_decode($assets['response'], JSON_PRETTY_PRINT);

            // echo '<pre>';
            // print_r($assets);


            
            // // Snippet For CSS
            // $snippet = "{% include 'alertcss' %}";
            // $body_tag = '</body>';
            // $new_body_tag = $snippet . $body_tag;
            // $theme_liquid = $assets['asset']['value'];
            // $new_theme_liquid = str_replace($body_tag, $new_body_tag, $theme_liquid);

            // // echo $new_theme_liquid;
            // if (strpos($assets['asset']['value'], $snippet) === false) {
            //     $array = array(
            //         'asset' => array(
            //             'key' => 'layout/theme.liquid',
            //             'value' => $new_theme_liquid
            //         )
            //     );
            //     $assets = shopify_call($token, $shop_url, "/admin/api/2022-10/themes/".$theme_id."/assets.json", $array, 'PUT');
            //     $assets = json_decode($assets['response'], JSON_PRETTY_PRINT);
            // }


            // Snippet For JS
            $snippet = "{% include 'alertjs' %}";
            $body_tag = '</body>';
            $new_body_tag = $snippet . $body_tag;
            $theme_liquid = $assets['asset']['value'];
            $new_theme_liquid = str_replace($body_tag, $new_body_tag, $theme_liquid);

            // echo $new_theme_liquid;
            if (strpos($assets['asset']['value'], $snippet) === false) {
                $array = array(
                    'asset' => array(
                        'key' => 'layout/theme.liquid',
                        'value' => $new_theme_liquid
                    )
                );
                $assets = shopify_call($token, $shop_url, "/admin/api/2022-10/themes/".$theme_id."/assets.json", $array, 'PUT');
                $assets = json_decode($assets['response'], JSON_PRETTY_PRINT);
            }
        }
    }
}