
<div class="container">

	<div class="row">

		<div class="col-md-3 col-lg-3">
			<div class="list-group adminbar catalog-bar">
				<? foreach ($categories as $id => $category):?>
					<a href="/catalog/<?=$category?>" class="list-group-item"><?=$category?></a>
				<? endforeach; ?>
			</div>
		</div>

		<div class="col-md-9 col-lg-9 catalog-content">

			<div class="row items-list">

				<?foreach ($items as $item):?>
					<div class="item col-sm-6 col-sm-6 col-md-4 col-lg-3">
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




	</div>
	

</div>

