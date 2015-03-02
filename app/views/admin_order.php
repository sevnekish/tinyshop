<div class="container col-lg-9 col-md-9 col-sm-12">
	<?if(!empty($success)):?>
		<div class="alert alert-success" role="alert"><?=$success?></div>
	<?endif;?>

	<?if(!empty($error)):?>
		<div class="alert alert-danger" role="alert"><?=$error?></div>
	<?endif;?>
	<div class="row">
	<div class="order_title">
		<h3>Order #<?=$order['order']['order_id']?></h3>
		<h3 class="pull-right">
			<?if ($order['order']['confirmed'] == 0):?>
				<i class="glyphicon glyphicon-remove" style="color:#FF0004;"></i> Not confirmed
			<?endif;?>
			<?if ($order['order']['confirmed'] == 1):?>
				<i class="glyphicon glyphicon-ok" style="color:#00A41E;"></i> Confirmed
			<?endif;?>
		</h3>

		<hr>
	</div>
	<div class="row">
		<div class="col-xs-6">
			<address>
				<strong>User Id:</strong><br>
					<?=$order['order']['user_id']?>
					<br>
				<strong>Shipped To:</strong><br>
					<?=$order['order']['name']?><br>
					<?=$order['order']['address']?><br>
			</address>


		</div>
	</div>
	<div class="row">
		<div class="col-sm-12 col-md-12 col-lg-12 order">

			<div class="panel panel-default">

				<div class="panel-heading">
					<h3 class="panel-title">Order summary</h3>
				</div>

				<div class="panel-body">
					<table class="table table-hover">
						<thead>
							<tr>
								<th>Product</th>
								<th>Quantity</th>
								<th class="text-center">Price</th>
								<th class="text-center">Total</th>
							</tr>
						</thead>
						<tbody>
							<?foreach ($order['order']['ordered_items'] as $item):?>
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
										</div>
									</div></td>
									<td class="col-sm-1 col-md-1" style="text-align: center"><strong><?=$item['quantity']?></strong></td>
									<td class="col-sm-1 col-md-1 text-center"><strong>$<?=$item['old_price']?></strong></td>
									<td class="col-sm-1 col-md-1 text-center"><strong>$<?echo $item['quantity'] * $item['old_price'];?></strong></td>
								</tr>
							<?endforeach;?>

							<tr>
								<td>   </td>
								<td>   </td>
								<td><h3>Total</h3></td>
								<td class="text-right"><h3><strong><?=$order['order']['sum']?></strong></h3></td>
							</tr>

						</tbody>
					</table>
				</div>

			</div>

			
		</div>
	</div>

	<div class="row adm-order-btn">

		<div class="col-lg-3 col-md-4 col-sm-4">
			<form name="buy-item" action="/confirm/<?=$order['order']['order_id']?>" method="post">
				<button class="btn btn-success <?if ($order['order']['confirmed']  == 1):?>disabled<?endif;?>" type="submit">
					<i class="glyphicon glyphicon-ok"></i> Confirm
				</button>
			</form>
		</div>

		<div class="col-lg-3 col-md-4 col-sm-4">
			<form name="buy-item" action="/delorder/<?=$order['order']['order_id']?>" method="post">
				<button class="btn btn-danger" type="submit">
					<i class="glyphicon glyphicon-remove"></i> Remove
				</button>
			</form>
		</div>
			

	</div>

	</div>
</div>
