<div class="container col-lg-9 col-md-9 col-sm-12">
	<div class="row">



					<div class="tabs">
						<ul class="nav nav-tabs">
							<li class="active">
								<a href="#tab-1" data-toggle="tab">
									All orders <span class="badge"><?=$count_orders?></span>
								</a>
							</li>
							<li>
								<a href="#tab-2" data-toggle="tab">
									Confirmed <span class="badge"><?=$count_orders_confirmed?></span>
								</a>
							</li>
							<li>
								<a href="#tab-3" data-toggle="tab">
									Not confirmed <span class="badge"><?=$count_orders_notconfirmed?></span>
								</a>
							</li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane fade in active" id="tab-1">
								
								<div class="table-responsive">

									<table id="mytable" class="table table-bordred table-striped">

										<thead>

											<th>Order id</th>
											<th>Sum</th>
											<th>Date</th>
											<th>Status</th>

										</thead>

										<tbody>
											<?foreach ($user_orders as $order):?>
												<tr>

													<td><a href="/account/order/<?=$order['order_id']?>"><?=$order['order_id']?></a></td>
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

												</tr>
											<?endforeach;?>
										</tbody>

									</table>

								<!-- end of responsive -->
								</div>

							</div>

							<div class="tab-pane fade" id="tab-2">

								<div class="table-responsive">

									<table id="mytable" class="table table-bordred table-striped">

										<thead>

											<th>Order id</th>
											<th>Sum</th>
											<th>Date</th>
											<th>Status</th>

										</thead>

										<tbody>
											<?foreach ($user_orders as $order):?>
												<?if ($order['confirmed'] == 1):?>
													<tr>

														<td><a href="/account/order/<?=$order['order_id']?>"><?=$order['order_id']?></a></td>
														<td><?=$order['sum']?></td>
														<td><?=$order['date']?></td>
														<td>
																<i class="glyphicon glyphicon-ok" style="color:#00A41E;"></i> Confirmed
														</td>

													</tr>
												<?endif;?>
											<?endforeach;?>
										</tbody>

									</table>

								<!-- end of responsive -->
								</div>

							</div>
							<div class="tab-pane fade" id="tab-3">
								
									<table id="mytable" class="table table-bordred table-striped">

										<thead>

											<th>Order id</th>
											<th>Sum</th>
											<th>Date</th>
											<th>Status</th>

										</thead>

										<tbody>
											<?foreach ($user_orders as $order):?>
												<?if ($order['confirmed'] == 0):?>
													<tr>

														<td><a href="/account/order/<?=$order['order_id']?>"><?=$order['order_id']?></a></td>
														<td><?=$order['sum']?></td>
														<td><?=$order['date']?></td>
														<td>
																<i class="glyphicon glyphicon-remove" style="color:#FF0004;"></i> Not confirmed
														</td>

													</tr>
												<?endif;?>
											<?endforeach;?>
										</tbody>

									</table>

								<!-- end of responsive -->
								</div>

							</div>
						</div>
					</div>







<!-- end of main container > row -->
		</div>

<!-- end of main container-->
	</div>



</div>




