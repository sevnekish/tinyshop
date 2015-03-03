
<!-- reviews -->
<div class="container">

	<div class="row col-sm-12 review-form">

		<?if(!empty($success)):?>
			<div class="alert alert-success" role="alert"><?=$success?></div>
		<?endif;?>

		<?if(!empty($error)):?>
			<div class="alert alert-danger" role="alert"><?=$error?></div>
		<?endif;?>

		<div class="col-sm-10 col-sm-offset-1 contact-form">

			<div class="row">
				<form id="contact" action="/addreview" method="post" class="form" role="form">

					<div class="row lead">
						<div id="stars-existing" class="starrr" data-rating='5'>
						</div>
						<input type="hidden" id="count-existing" name="rating" value='5'>
					</div>

					<div class="row">
						<div class="col-xs-6 col-md-6 form-group">
							<input class="form-control" id="name" name="name" placeholder="Name" type="text" required autofocus
							<?if ($user_info):?> value="<?=$user_info['name']?>" readonly<?endif;?>/>
						</div>

						<div class="col-xs-6 col-md-6 form-group">
							<input class="form-control" id="email" name="email" placeholder="Email" type="email" required
							<?if ($user_info):?> value="<?=$user_info['email']?>" readonly<?endif;?>/>
						</div>
					</div>

					<textarea class="form-control" id="message" name="review" placeholder="Message" rows="5"></textarea>
					<br />



					<button class="btn btn-primary pull-right" type="submit">Add review</button>


				</form>
			</div>

		</div>
		
	</div>


	<div class="row col-sm-10 col-sm-offset-1 reviews">

		<?foreach ($reviews as $review):?>

			<div class="row review">
				<div class="col-sm-3">
					<?for ($i = 0; $i < $review['rating']; $i++):?>
						<span class='fa fa-star'></span>
					<?endfor;?>

					<?for ($i = 0; $i < (5 - $review['rating']); $i++):?>
						<span class='fa fa-star-o'></span>
					<?endfor;?>
					<p>
						by <a href="mailto:<?=$review['email']?>"><?=$review['name']?></a>
					</p>
					<p><?=$review['date']?></p>
				</div>

				<div class="col-sm-9">
					<?if ($userparams['role'] === 'admin'):?>
						<div class="pull-right">
							<form name="buy-item" action="/delreview/<?=$review['id']?>" method="post">
								<button class="btn btn-danger" type="submit">
									<i class="glyphicon glyphicon-remove"></i> Remove
								</button>
							</form>
						</div>
					<?endif;?>
					
					<?=nl2br($review['review']);?>
				</div>
			</div>

			<hr>

		<?endforeach;?>

		<div class="row">

			<div class="pages">
				<nav>
					<ul class="pagination">

						<li <?if($cur_page == 1):?>class="disabled"<?endif;?>>
							<a aria-label="Previous" href="/reviews/<?=($cur_page - 1)?>">
								<span aria-hidden="true">«</span>
							</a>
						</li>


						<?if(($cur_page - 2) > 0):?>
							<li >
								<a href="/reviews/<?=($cur_page - 2)?>">
									<?=($cur_page - 2)?> 
								</a>
							</li>
						<?endif;?>

						<?if(($cur_page - 1) > 0):?>
							<li >
								<a href="/reviews/<?=($cur_page - 1)?>">
									<?=($cur_page - 1)?> 
								</a>
							</li>
						<?endif;?>

						<li class="active">
							<a href="/reviews/<?=$cur_page?>">
								<?=$cur_page?> 
							</a>
						</li>

						<?if(($cur_page + 1) <= $total_pages):?>
							<li >
								<a href="/reviews/<?=($cur_page + 1)?>">
									<?=($cur_page + 1)?> 
								</a>
							</li>
						<?endif;?>

						<?if(($cur_page + 2) <= $total_pages):?>
							<li >
								<a href="/reviews/<?=($cur_page + 2)?>">
									<?=($cur_page + 2)?> 
								</a>
							</li>
						<?endif;?>

						<li <?if($cur_page == $total_pages):?>class="disabled"<?endif;?>>
							<a aria-label="Next" href="/reviews/<?=($cur_page + 1)?>">
								<span aria-hidden="true">»</span>
							</a>
						</li>

					</ul>
				</nav>
			</div>
			
		</div>

	</div>

	

</div>

