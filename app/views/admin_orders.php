<div class="container col-lg-9 col-md-9 col-sm-12">

		<div class="row filters">

			<ul class="nav nav-pills adm-orders">
				<li role="presentation">
					<a href="/adminbar/showorders/1/all">
						All <span class="badge"><?=$count_orders?></span>
					</a>
				</li>
				<li role="presentation">
					<a href="/adminbar/showorders/1/confirmed">
						Confirmed <span class="badge"><?=$count_orders_confirmed?></span>
					</a>
				</li>
				<li role="presentation">
					<a href="/adminbar/showorders/1/notconfirmed">
						Not confirmed <span class="badge"><?=$count_orders_notconfirmed?></span>
					</a>
				</li>
			</ul>

		</div>

		<div class="row orders-list">

			<?if(!empty($success)):?>
				<div class="alert alert-success" role="alert"><?=$success?></div>
			<?endif;?>

			<?if(!empty($error)):?>
				<div class="alert alert-danger" role="alert"><?=$error?></div>
			<?endif;?>
			
			<table id="mytable" class="table table-bordred table-striped">

				<thead>

					<th>Order id</th>
					<th>User id</th>
					<th>Sum</th>
					<th>Date</th>
					<th>Status</th>
					<th>Confirm</th>
					<th>Remove</th>

				</thead>

				<tbody>
					
					<?foreach ($orders as $order):?>
						<tr>

							<td><a href="/adminbar/order/<?=$order['order_id']?>"><?=$order['order_id']?></a></td>
							<td>
								<?=$order['user_id']?>
							</td>
							<td><?=$order['sum']?></td>
							<td><?=$order['date']?></td>
							<td>
								<?if ($order['confirmed'] == 0):?>
									<i class="glyphicon glyphicon-remove" style="color:#FF0004;"></i> Not confirmed
								<?endif;?>
								<?if ($order['confirmed'] == 1):?>
									<i class="glyphicon glyphicon-ok" style="color:#00A41E;"></i> Confirmed
								<?endif;?>
							</td>
							<td>
								<form name="buy-item" action="/confirm/<?=$order['order_id']?>" method="post">
									<button class="btn btn-sm btn-success <?if ($order['confirmed'] == 1):?>disabled<?endif;?>" type="submit">
										<i class="glyphicon glyphicon-ok"></i>
									</button>
								</form>
							</td>
							<td>
								<form name="buy-item" action="/delorder/<?=$order['order_id']?>" method="post">
									<button class="btn btn-sm btn-danger" type="submit">
										<i class="glyphicon glyphicon-remove"></i>
									</button>
								</form>
							</td>

						</tr>
					<?endforeach;?>

				</tbody>

			</table>

		</div>


		<div class="row">

			<div class="pages">
				<nav>
					<ul class="pagination">

						<li <?if($cur_page == 1):?>class="disabled"<?endif;?>>
							<a aria-label="Previous" href="/adminbar/showorders/<?=($cur_page - 1)?>/<?=$confirmed?>">
								<span aria-hidden="true">«</span>
							</a>
						</li>


						<?if(($cur_page - 2) > 0):?>
							<li >
								<a href="/adminbar/showorders/<?=($cur_page - 2)?>/<?=$confirmed?>">
									<?=($cur_page - 2)?> 
								</a>
							</li>
						<?endif;?>

						<?if(($cur_page - 1) > 0):?>
							<li >
								<a href="/adminbar/showorders/<?=($cur_page - 1)?>/<?=$confirmed?>">
									<?=($cur_page - 1)?> 
								</a>
							</li>
						<?endif;?>

						<li class="active">
							<a href="/adminbar/showorders/<?=$cur_page?>/<?=$confirmed?>">
								<?=$cur_page?> 
							</a>
						</li>

						<?if(($cur_page + 1) <= $total_pages):?>
							<li >
								<a href="/adminbar/showorders/<?=($cur_page + 1)?>/<?=$confirmed?>">
									<?=($cur_page + 1)?> 
								</a>
							</li>
						<?endif;?>

						<?if(($cur_page + 2) <= $total_pages):?>
							<li >
								<a href="/adminbar/showorders/<?=($cur_page + 2)?>/<?=$confirmed?>">
									<?=($cur_page + 2)?> 
								</a>
							</li>
						<?endif;?>

						<li <?if($cur_page == $total_pages):?>class="disabled"<?endif;?>>
							<a aria-label="Next" href="/adminbar/showorders/<?=($cur_page + 1)?>/<?=$confirmed?>">
								<span aria-hidden="true">»</span>
							</a>
						</li>

					</ul>
				</nav>
			</div>
			
		</div>


</div>
