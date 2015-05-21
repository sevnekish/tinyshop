
<div class="container">

  <div class="row">

    <div class="col-md-3 col-lg-3">
      <div class="list-group catalog-bar">
        <? foreach ($categories as $id => $category):?>
          <a href="/catalog/<?=$category?>" class="list-group-item"><?=$category?></a>
        <? endforeach; ?>
      </div>
    </div>

    <div class="col-md-9 col-lg-9 item-alone">

      <div class="row name">
        <p><?echo $item['brand'] . ' ' . $item['model'];?></p>
      </div>

      <div class="row main">

        <div class="col-sm-6 col-md-6 col-lg-6 pic">
          <img src="/<?echo $image_dir . $item['photo'];?>" class="img-responsive" alt="a"/>
        </div>

        <div class="col-sm-6 col-md-6 col-lg-6 info-price">
          <div>
            <p class="price-text-color">
              $<?=$item['price']?>
            </p>
          </div>
          

          <?if ($item['instock'] > 0):?>
            <div>
              <span class="label label-success">In stock</span>
            </div>

            <div class="buy-item">
              <form name="buy-item" action="/addtocart/<?=$item['id']?>" method="post">
                <button class="btn btn-lg btn-primary" type="submit">
                  <i class="fa fa-2x fa-cart-plus"></i> Add to cart
                </button>
              </form>
            </div>

          <?endif;?>

          <?if ($item['instock'] <= 0):?>
            <div>
              <span class="label label-danger">Out of stock</span>
            </div>

            <div class="buy-item">

              <button class="btn btn-lg btn-danger disabled">
                Not available now
              </button>

            </div>
          <?endif;?>

          


          
        </div>

      </div>

      <div class="row features">
        <div class="panel panel-primary">
          <div class="panel-heading">
            <h3 class="panel-title"><?echo $item['brand'] . ' ' . $item['model'];?> features</h3>
          </div>

          <div class="panel-body">
             <?=nl2br($item['description']);?>
          </div>
        </div>
      </div>

      <div class="row characteristics">
        <div class="panel panel-primary">
          <div class="panel-heading">
            <h3 class="panel-title">Technical Details</h3>
          </div>

          <div class="panel-body">
            <?=nl2br($item['characteristics']);?>
          </div>
        </div>
      </div>

    </div>


  </div>


</div>

