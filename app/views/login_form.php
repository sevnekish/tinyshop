<!-- signin form -->
<div class="container">
	<div>

	<form class="form-signin" action="/login" method="post">

		<?if(!empty($error)):?>
			<div class="alert alert-danger" role="alert"><?=$error?></div>
		<?endif;?>

		<h2 class="form-signin-heading">Please sign in</h2>

		<input name="email" id="inputEmail" class="form-control" type="email" autofocus="" required="" placeholder="Email address">
		<label class="sr-only" for="inputPassword">Password</label>
		<input name="password" id="inputPassword" class="form-control" type="password" required="" placeholder="Password" autocomplete="off">
<!-- 		<div class="checkbox">
			<label>
				<input name= "remember" type="checkbox" value="remember-me">
				Remember me
			</label>
		</div> -->
		<br>
		<button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
		<?if(!empty($urlRedirect)):?>
			<div class="panel panel-warning" role="alert">You will redirect to "<?=$urlRedirect?>" upon login</div>
		<?endif;?>
	</form>
	</div>
</div>