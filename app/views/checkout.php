		<div class="checkout">

			<form name="checkout" class="checkout-form" action="/checkout" method="post">

				<?if(!empty($success)):?>
					<div class="alert alert-success" role="alert"><?=$success?></div>
				<?endif;?>

				<?if(!empty($error)):?>
					<div class="alert alert-danger" role="alert"><?=$error?></div>
				<?endif;?>


				<label>Name</label>
					<input name="name" type="text" value="<?=$user_info['name']?>" class="form-control input-xlarge" autocomplete="off">

				<label>Email</label>
					<input name="email" type="text" value="<?=$user_info['email']?>" class="form-control input-xlarge" autocomplete="off">

				<label>Telephone</label>
					<input name="telephone" type="text" value="<?=$user_info['telephone']?>" class="form-control input-xlarge" autocomplete="off">

				<label>Address</label>
					<input name="address" type="text" value="<?=$user_info['address']?>" class="form-control input-xlarge" autocomplete="off">

				<div>
					<button class="btn btn-lg btn-primary pull-right btn-adminbar" type="submit">
						Confirm and buy
					</button>
				</div>
			</form>

		</div>