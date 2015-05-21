<div class="container">
  <div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12 shopping-cart">

      <?if(!empty($success)):?>
        <div class="alert alert-success" role="alert"><?=$success?></div>
      <?endif;?>

      <?if(!empty($error)):?>
        <div class="alert alert-danger" role="alert"><?=$error?></div>
      <?endif;?>
      
      <?if(empty($success)):?>
        <?if(!($cart_count > 0)):?>
          <div class="alert alert-danger" role="alert">Your shopping cart is empty!</div>
        <?endif;?>
      <?endif;?>


      <table class="table table-hover">
        <thead>
          <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th class="text-center">Price</th>
            <th class="text-center">Total</th>
            <th> </th>
          </tr>
        </thead>
        <tbody>
          <?foreach ($cart_array as $order):?>
            <?foreach ($order as $item):?>
              <tr>
                <td class="col-sm-8 col-md-6">
                <div class="media">
                  <a class="thumbnail pull-left" href="/item/<?=$item['id']?>">
                    <img class="media-object" src="/<?echo $image_dir . $item['photo'];?>">
                  </a>
                  <div class="media-body">
                    <h4 class="media-heading">
                      <a href="/item/<?=$item['id']?>">
                        <?echo $item['brand'] . ' ' . $item['model'];?>
                      </a>
                    </h4>
                    <span>Status: </span>

                    <?if ($item['instock'] > 0):?>
                      <span class="text-success">
                        <strong>In Stock</strong>
                      </span>
                    <?endif;?>

                    <?if ($item['instock'] <= 0):?>
                      <span class="text-danger">
                        <strong>Out of stock</strong>
                      </span>
                    <?endif;?>
                  </div>
                </div></td>
                <td class="col-sm-1 col-md-1" style="text-align: center"><strong><?=$item['quantity']?></strong></td>
                <td class="col-sm-1 col-md-1 text-center"><strong>$<?=$item['price']?></strong></td>
                <td class="col-sm-1 col-md-1 text-center"><strong>$<?echo $item['quantity'] * $item['price'];?></strong></td>
                <td class="col-sm-1 col-md-1">
                  <form name="checkout" action="/delfromcart/<?=$item['id']?>" method="post">
                    <button class="btn btn-danger" type="submit">
                      <span class="glyphicon glyphicon-remove"></span> Remove
                    </button>
                  </form>
                </td>
              </tr>
              <?endforeach;?>
          <?endforeach;?>
          

          <tr>
            <td>   </td>
            <td>   </td>
            <td>   </td>
            <td><h3>Total</h3></td>
            <td class="text-right"><h3><strong><?=$sum?></strong></h3></td>
          </tr>
          <tr>
            <td>   </td>
            <td>   </td>
            <td>   </td>
            <td>
              <a href="/catalog" type="button" class="btn btn-default">
                <span class="glyphicon glyphicon-shopping-cart"></span> Continue Shopping
              </a>
            </td>
            <td>
              <a href="/checkout" class="btn btn-success<?if (!($cart_count > 0)):?> disabled <?endif;?>">
                Checkout <span class="glyphicon glyphicon-play"></span>
              </a>
            </td>
          </tr>


        </tbody>
      </table>
    </div>
  </div>
</div>