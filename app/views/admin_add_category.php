<div class="col-lg-6 col-md-6 col-sm-6">

		<form name="addcategory" class="form-new-category" action="/addcategory" method="post">

			<?if(!empty($success)):?>
				<div class="alert alert-success" role="alert"><?=$success?></div>
			<?endif;?>

			<?if(!empty($error)):?>
				<div class="alert alert-danger" role="alert"><?=$error?></div>
			<?endif;?>

			<label>New category</label>
				<input name="category" type="text" value="" class="form-control input-xlarge" autocomplete="off" required>

			<div>
				<button class="btn btn-lg btn-primary pull-right btn-adminbar" type="submit">Add category</button>
			</div>
		</form>

</div>