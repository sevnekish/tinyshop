
<div class="container">
  <div id="carousel" class="carousel slide">
    <!-- sliders indicators -->
    <ol class="carousel-indicators">
      <li calss="active" data-target="#carousel" data-slide-to="0"></li>
      <li data-target="#carousel" data-slide-to="1"></li>
      <li data-target="#carousel" data-slide-to="2"></li>
    </ol>

    <!-- slide -->
    <div class="carousel-inner">
      <div class="item active">
        <a href="/item/50"><img src="content/images/LenovoS660PW.png" alt="" >
        </a>
      </div>
      <div class="item">
        <a href="/item/19"><img src="content/images/HTC_Desire_EyePW.png" alt="">
        </a>
      </div>
      <div class="item">
        <a href="/item/43"><img src="content/images/Nokia_Lumia635PW.png" alt="">
        </a>
      </div>
    </div>

    <!-- sliding arrows -->
    <a href="#carousel" class="left carousel-control" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left"></span>
    </a>
    <a href="#carousel" class="right carousel-control" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right"></span>
    </a>
  </div>
  <br>
  <br>
  <br>
  
  <div class="row items-list">

    <?foreach ($items as $item):?>
      <div class="item col-xs-12 col-sm-6 col-md-4 col-lg-2">
        <div class="col-item item-<?=$item['id']?>">
          <div class="photo">
            <a href="/item/<?=$item['id']?>">
              <img src="/<?echo $image_dir . $item['photo'];?>" class="img-responsive" alt="a" />
            </a>
          </div>
          <div class="info">
            <div class="row item-name">
              <h4>
                <a href="/item/<?=$item['id']?>"><?echo $item['brand'] . ' ' . $item['model'];?></a>
              </h4>
            </div>

            <div class="row price-buy">
              <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
                <h5 class="price-text-color">
                  <b>$<?=$item['price']?></b>
                </h5>
              </div>
              <div class="col-xs-6 col-sm-6 col-md-8 col-lg-8">

                <?if ($item['instock'] > 0):?>

                  <form name="buy-item" action="/addtocart/<?=$item['id']?>" method="post">
                    <button class="btn btn-primary <?if ($userparams['role'] === 'admin'):?>disabled<?endif;?>" type="submit">
                      Add to cart
                    </button>
                  </form>

                <?endif;?>

                <?if ($item['instock'] <= 0):?>

                  <div class="buy-item">

                    <button class="btn btn-danger disabled">
                      Not available
                    </button>

                  </div>
                <?endif;?>

              </div>
            </div>
            
          </div>
        </div>
      </div>
    <?endforeach;?>

  </div>


</div>

