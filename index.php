<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <title>Shopify App</title>
  </head>
  <body>

	<div class="container">
		<?php
		include_once("inc/connection.php");
		include_once("header.php");
		include_once("inc/functions.php");
		

    // $array = array(
    //   'webhook' => array(
    //     'topic' => 'products/update',
    //     'address' => ' https://52ea-175-107-217-5.in.ngrok.io/apps/example_app/webhooks/delete.php',
    //     'format' => 'json'
    //   )
    // );

    // $parsedUrl = parse_url('https://' . $shop_url );
    // $host = explode('.', $parsedUrl['host']);
    // $subdomain = $host[0];
    // echo  $subdomain. '<br>';
    // echo $parsedUrl['host'];
    // $shop = $subdomain;

    // $webhook = shopify_call($token, $shop_url, "/admin/api/2020-07/webhooks.json", $array, 'POST');
    // $webhook = json_decode($webhook['response'], JSON_PRETTY_PRINT);

    // print_r($webhook);

    // $webhook = shopify_call($token, $shop_url, "/admin/api/2019-10/webhooks.json", array(), 'GET');
    // $webhook = json_decode($webhook['response'], JSON_PRETTY_PRINT);

    // echo print_r( $webhook );
    // echo $webhook['webhooks'][0]['id'];

    // $webhook = shopify_call($token, $shop_url, "/admin/api/2019-10/webhooks".$webhook['webhooks'][0]['id'].".json", array(), 'DELETE');
    // $webhook = json_decode($webhook['response'], JSON_PRETTY_PRINT);
    

		// include_once("create_popup.php");
		// include_once("products.php");
		// include_once("orders.php");

    $array = array(
      'limit' => 4
    );

    $products = rest_api($token, $shop_url, "/admin/api/2021-07/products.json", $array, 'GET');
    $headers = $products['headers'];
    $products = json_decode($products['data'], true);


    // foreach ($headers as $key => $value) {
    //   echo '<div>'.$key.' => '.$value.'</div>';
    // }

    $nextPageURL = str_btwn($headers['link'], '<', '>');
    $nextPageURLparam = parse_url($nextPageURL);
    parse_str($nextPageURLparam['query'], $value);
    $page_info = $value['page_info'];

    // echo $page_info;

    // $nextPageURL = str_btwn($headers['link'], '<', '>');
    // $nextPageURLparam = parse_url($nextPageURL);
    // parse_str($nextPageURLparam['query'], $value);
    // $page_info = $value['page_info'];

		?>

    <div>
      <ul id="product-list">
        <?php
          foreach($products as $product){ 
              foreach($product as $value){ 
              echo '<li>' . $value['title'] . '</li>';
              }
          }

        ?>
      </ul>
      <button type="button" data-info="" data-rel="previous" data-store="<?php echo $shop_url; ?>">Previous</button>
      <button type="button" data-info="<?php echo $page_info; ?>" data-rel="next" data-store="<?php echo $shop_url; ?>">Next</button>

    </div>


	</div>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
  
	<script src="https://cdnjs.cloudflare.com/ajax/libs/printThis/1.15.0/printThis.min.js"></script>
    <script>
      $('#printBtn').on('click', function(e) {
        $('#printThisInvoice').printThis();
      });
    </script>

    <script>
      // var shop = '<?php //echo $shop_url; ?>';
      // $('div[product-id]').on('click', function (e) {
      //   $.ajax({
      //     method: 'POST',
      //     data: {
      //       url: shop,
      //       id: $(this).attr('product-id'),
      //       type: 'GET'
      //     },
      //     url:'ajax.php',
      //     dataType: 'json',
      //     success:function(json){
      //       console.log(json);

      //       $('#productName').val(json['title']);
      //       $('#productDescription').val(json['description']);

      //       $('#productCollection option').each(function(i) {
      //         var optionCollection = $(this).val();

      //         json['collections'].forEach(function(productCollection) {
      //           if(productCollection['id'] == optionCollection) {
      //             $('#productCollection option[value=' + optionCollection + ']').attr('selected', 'selected');
      //           }

      //         });
      //       });


      //       $('#SaveProduct').attr('product-id', json['id']);
      //       $('#productsModal').modal('show');
      //     }   
      //   }); 
      // });


      // $('#productsModal').on('hide.bs.modal', function() {
      //   $('#SaveProduct').attr('product-id', '');
      //   $('#productCollection').val([]);
      // });

      // $('#SaveProduct').on('click', function() {
      //   var productID = $(this).attr('product-id');

      //   $.ajax({
      //     method: 'POST',
      //     data: {
      //       url: shop,
      //       id: productID,
      //       product: $('#productForm').serialize(),
      //       type: 'PUT'
      //     },
      //     url:'ajax.php',
      //     dataType: 'html',
      //     success:function(json){
      //       console.log(json);
      //     }   
      //   }); 
      // });
    </script>


  <script>
    $('button').on('click', function(e) {
      var data_info = $(this).attr('data-info');
      var data_rel = $(this).attr('data-rel');
      var data_store = $(this).attr('data-store');
      
      if(data_info != '') {
        console.log(data_info);
        $.ajax({
          type: "GET",
          url: "pagination.php", 
          data: {
            page_info: data_info,
            rel: data_rel,
            url: data_store
          },           
          dataType: "json",               
          success: function(response) {
            console.log(response);

            if( response['prev'] != '' ) {
              $('button[data-rel="previous"]').attr('data-info', response['prev']);
            } else {
              $('button[data-rel="previous"]').attr('data-info', "");
            }

            if( response['next'] != '' ) {
              $('button[data-rel="next"]').attr('data-info', response['next']);
            } else {
              $('button[data-rel="next"]').attr('data-info', "");
            }

            if( response['html'] != '' ) {
              $('#product-list').html(response['html']);
            }
          }
        });
      }

    });
  </script>

  </body>
</html>