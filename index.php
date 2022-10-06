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
		include_once("products.php");
		?>
	</div>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>


    <script>
      var shop = '<?php echo $shop_url; ?>';
      $('div[product-id]').on('click', function (e) {
        $.ajax({
          method: 'POST',
          data: {
            url: shop,
            id: $(this).attr('product-id'),
            type: 'GET'
          },
          url:'ajax.php',
          dataType: 'json',
          success:function(json){
            console.log(json);

            $('#productName').val(json['title']);
            $('#productDescription').val(json['description']);

            $('#productCollection option').each(function(i) {
              var optionCollection = $(this).val();

              json['collections'].forEach(function(productCollection) {
                if(productCollection['id'] == optionCollection) {
                  $('#productCollection option[value=' + optionCollection + ']').attr('selected', 'selected');
                }

              });
            });


            $('#SaveProduct').attr('product-id', json['id']);
            $('#productsModal').modal('show');
          }   
        }); 
      });


      $('#productsModal').on('hide.bs.modal', function() {
        $('#SaveProduct').attr('product-id', '');
        $('#productCollection').val([]);
      });

      $('#SaveProduct').on('click', function() {
        var productID = $(this).attr('product-id');

        $.ajax({
          method: 'POST',
          data: {
            url: shop,
            id: productID,
            product: $('#productForm').serialize(),
            type: 'PUT'
          },
          url:'ajax.php',
          dataType: 'html',
          success:function(json){
            console.log(json);
          }   
        }); 
      });
    </script>
  </body>
</html>