<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>LenovoShop</title>

		<!-- style -->
		<link href="/app/content/css/bootstrap.css" rel="stylesheet">
		<link href="/app/content/css/bootstrap-theme.css" rel="stylesheet">
		<link href="/app/content/css/style.css" rel="stylesheet">
		<!-- font-icon -->
		<link href="/app/content/css/font-awesome.css" rel="stylesheet">

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="/app/scripts/bootstrap.js"></script>
		<!-- Add's 'action' class to active navigation tab -->
		<script src="/app/scripts/navbar_tab_activator.js"></script>
		<!-- Add's 'action' class to active admin_bar tab -->
		<script src="/app/scripts/admin_bar_tab_activator.js"></script>
		<!-- Upload file script -->
		<script src="/app/scripts/file_input.js"></script>
		<!-- Makes all items in catalog equal height-->
		<script src="/app/scripts/catalog_div_height.js"></script>
		<!-- Registration form checking-->
		<script src="/app/scripts/registration_form.js"></script>
		<!--  Add's 'action' class to active admin_bar pills for orders-->
		<script src="/app/scripts/admin_orders_activator.js"></script>
		<!--  Star plugin-->
		<script src="/app/scripts/starplugin.js"></script>
<!-- header -->
	<div class="navbar navbar-default navbar-static-top">
		<div class="container">
		<!-- navbar -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#responsive-menu">
					<span class="sr-only">Open-navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<!-- <a href="/" class="navbar-brand"><i class="fa fa-arrows-v"></i>Tiny Shop</a> -->
				<a href="/" class="navbar-brand"><img src="/app/content/images/TinyShopLogoW3.png" height="40"/></a>
			</div>
			<div class="collapse navbar-collapse" id="responsive-menu">

				<ul class="nav navbar-nav navigation navmenu">
					<li><a href="/">Home</a></li>
					<!-- <li><a href="/catalog"></a></li> -->
					<li class="dropdown">
						<a href="/catalog" class="dropdown-toggle" data-toggle="dropdown">Catalog <b class="caret"></b></a>
						<ul class="dropdown-menu">

							<? foreach ($categories as $id => $category):?>
								<li><a href="/catalog/<?=$category?>"><?=$category?></a></li>
							<? endforeach; ?>
								
						</ul>
					</li>
					<li><a href="/reviews">Reviews</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right navigation">

					<? if ($userparams['role'] === 'guest'): ?>
					<li class="dropdown">

						<a href="/logreg" class="dropdown-toggle" data-toggle="dropdown">
							<i class="fa fa-sign-in"></i> Login / Registrate
						</a>
						<ul class="dropdown-menu">
							<li>
								<a href="/login">Login</a>
							</li>

							<li class="divider" ></li>
							
							<li>
								<a href="/registration">Registrate</a>
							</li>
						</ul>

					</li>
					<? endif;?>

					<? if ($userparams['role'] === 'user'): ?>
					<li>
						<a href="/account/userinfo">
							<i class="fa fa-user"></i> <? echo $userparams['email']?>
						</a>
					</li>
					<? endif;?>

					<? if ($userparams['role'] === 'admin'): ?>
					<li>
						<a href="/adminbar">
							<i class="fa fa-wrench"></i> Administrator
						</a>
					</li>
					<? endif;?>


					<li class="<?if ($userparams['role'] === 'admin'):?>disabled<?endif;?>">
						<a class="cart" href="/cart">
							<i class="fa fa-shopping-cart"></i> Cart: <?=$cart_count?>
						</a>
					</li>

				</ul>

			</div>

		</div>
	</div>

	<div class="container content">