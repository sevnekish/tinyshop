<?php 
require 'vendor/autoload.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
// $app->add(new \Slim\Middleware\SessionCookie()); not work well, beacause we use $_SESSION => session_start();
session_start();


$app -> config(array('templates.path' => 'app/views'));

$app->notFound(function () use ($app) {
	$app->render('404.php');
});

$image_dir = 'app/content/images/catalog/';

$user = UsersFactory::createUser();

$cart = new ShoppingCart();


$authentication = function($app, $user, $authentication_role) {
	return function() use ($app, $user, $authentication_role) {
		$user_role = $user -> getRole();
		if ($authentication_role != $user_role) {
			switch($user_role){
				case 'admin': $app -> redirect("/adminbar"); break;
				case 'user': $app -> redirect("/account"); break;
				case 'guest': 
					$app -> flash('error', 'Login required');
					$app -> setCookie('urlRedirect', $app -> request() -> getPathInfo(), '4 minutes');
					$app -> redirect("/login");
					break;
				}
		}
	};
};

$access_denied = function($app, $user, $denied_user_role) {
	return function() use ($app, $user, $denied_user_role) {
		$user_role = $user -> getRole();
		if ($denied_user_role == $user_role) {
			switch($denied_user_role){
				case 'admin': $app -> redirect("/adminbar"); break;
				case 'user': $app -> redirect("/account"); break;
				case 'guest': 
					$app -> flash('error', 'Login required');
					$app -> setCookie('urlRedirect', $app -> request() -> getPathInfo(), '4 minutes');
					$app -> redirect("/login");
					break;
				}
		}
	};
};


$app -> hook('slim.before.dispatch', function() use ($app, $user, $cart, $image_dir) {
	$userparams = $user -> getParams();
	$categories = $user -> getCategories();
	$cart_count = $cart -> getCount();

	$flash = $app -> view() -> getData('flash');
	$error = '';
	$success = '';
	if (isset($flash['error'])) {
		$error = $flash['error'];
	}
	if (isset($flash['success'])) {
		$success = $flash['success'];
	}

	$app -> view() -> setData(array(
									'userparams' => $userparams,
									'cart_count' => $cart_count,
									'image_dir' => $image_dir,
									'error' => $error,
									'success' => $success
								));
	$app -> render('header.php', array('categories' => $categories));
});

$app -> hook('slim.after.dispatch', function() use ($app, $user) {
	$app -> render('footer.php');
});

$app -> get("/", function() use ($app, $user) {

	$items = $user -> getItemsRandom(6);

	$app -> render('home.php', array('items' => $items));
});

$app -> get("/logreg", function() use ($app) {
	$app -> redirect("/");
});

$app -> get("/catalog", function() use ($app, $user) {

	$items = $user -> getItemsRandom(12);

	$app -> render('catalog_main.php', array(
											'items' => $items
										));
});

$app -> get("/catalog/:category(/:page(/:sort))", function($category, $page = 1, $sort = 'cheap')
													use ($app, $user) {

	$items_per_page = 12;
	
	if(!$category_id = Validator::find_matches('categories', 'category', $category)) {
		$app -> notFound();
	}

	if(!$total_items = $user -> countItems($category_id)) {
		$app -> notFound();
	}

	$total_pages = $user -> countPages($total_items, $items_per_page);

	if (!$items = $user -> getItems($category_id, $sort, $total_items, $items_per_page, $page)) {
		$app -> notFound();
	}

	$app -> render('catalog.php', array(
										'cur_category' => $category,
										'cur_sort' => $sort,
										'total_pages' => $total_pages,
										'cur_page' => $page,
										'items' => $items
									));
});

$app -> get("/item/:id", function($id) use ($app, $user) {

	if (!$item = $user -> getItem($id)) {
		$app -> notFound();
	}

	$app -> render('item.php', array(
										'item' => $item
									));
});

$app -> get("/reviews(/:page)", function($page = 1) use ($app, $user) {
	$reviews_per_page = 6;

	if(!$total_reviews = $user -> countReviews()) {
		$app -> notFound();
	}

	$total_pages = $user -> countPages($total_reviews, $reviews_per_page);

	if (!$reviews = $user -> getReviews($total_reviews, $reviews_per_page, $page)) {
		$app -> notFound();
	}

	$user_info = $user -> getInfo();
	$app -> render('reviews.php', array(
										'user_info' => $user_info,
										'total_pages' => $total_pages,
										'cur_page' => $page,
										'reviews' => $reviews
									));
});

$app -> get("/contacts", function() use ($app) {
	$app -> render('contacts.php');
});


$app -> get("/cart",$access_denied($app, $user, 'admin'), function() use ($app, $cart, $user) {

	$cart_array = $cart -> getCartArray();

	$sum = $cart -> getSum($cart_array);

	$app -> render('shopping_cart.php', array(
										'cart_array' => $cart_array,
										'sum' => $sum
									));
});

$app -> get("/checkout",$access_denied($app, $user, 'admin'), function() use ($app, $user, $cart) {

	$cart_count = $app -> view() -> getData('cart_count');

	if (!($cart_count > 0)) {
		$app -> redirect('/cart');
	}

	$user_role = $user -> getRole();

	$user_info = array();

	if ($user_role == 'user') {
		$user_info = $user -> getInfo();
	}

	$app -> render('checkout.php', array(
										'user_info' => $user_info
									));
});

$app -> get("/account", $authentication($app, $user, 'user'), function() use ($app) {
	$app -> redirect("/account/userinfo");
});

$app -> group("/account", $authentication($app, $user, 'user'), function() use ($app, $user) {

	$app -> get("/userinfo", function() use ($app, $user) {

		$user_info = $user -> getInfo();

		$content = $app -> view() -> fetch('account_userinfo.php',
														array(
																'user_info' => $user_info
															));
		$app -> render('account_index.php', array(
													'account_content' => $content
												));
	});


	$app -> get("/showorders", function() use ($app, $user) {

		$user_orders = $user -> getOrders();

		$count_orders = $user -> countOrders();

		$count_orders_confirmed = $user -> countOrders('confirmed');

		$count_orders_notconfirmed = $count_orders - $count_orders_confirmed;

		$content = $app -> view() -> fetch('account_orders.php',
														array(
																'user_orders' => $user_orders,
																'count_orders' => $count_orders,
																'count_orders_confirmed' => $count_orders_confirmed,
																'count_orders_notconfirmed' => $count_orders_notconfirmed
															));
		$app -> render('account_index.php', array(
													'account_content' => $content
												));
	});

	$app -> get("/order/:order_id", function($order_id) use ($app, $user) {

		if (!$user_order = $user -> getOrder($order_id)) {
			$app -> notFound();
		}

		$content = $app -> view() -> fetch('account_order.php',
														array(
																'user_order' => $user_order
															));
		$app -> render('account_index.php', array(
													'account_content' => $content
												));
	});
});

$app -> get("/adminbar", $authentication($app, $user, 'admin'), function() use ($app) {
	$app -> redirect("/adminbar/showorders");
});

$app -> group("/adminbar", $authentication($app, $user, 'admin'), function() use ($app, $user) {

	$app -> get("/showorders(/:page(/:confirmed))", function($page = 1, $confirmed = 'all') use ($app, $user) {

		$orders_per_page = 4;//12
		

		if(($total_orders = $user -> countOrders($confirmed)) === false) {
			$app -> notFound();
		}

		$count_orders_all = $user -> countOrders('all');

		$count_orders_confirmed = $user -> countOrders('confirmed');

		$count_orders_notconfirmed = $count_orders_all - $count_orders_confirmed;
		
		$total_pages = $user -> countPages($total_orders, $orders_per_page);

		if (($orders = $user -> getOrders($confirmed, $total_orders, $orders_per_page, $page)) === false) {
			$app -> notFound();
		}


		$content = $app -> view() -> fetch('admin_orders.php', array(
																'confirmed' => $confirmed,
																'total_pages' => $total_pages,
																'cur_page' => $page,
																'count_orders' => $count_orders_all,
																'count_orders_confirmed' => $count_orders_confirmed,
																'count_orders_notconfirmed' => $count_orders_notconfirmed,
																'orders' => $orders
															));

		$app -> render('admin_bar_index.php', array('admin_bar_content' => $content));

	});

	$app -> get("/order/:order_id", function($order_id) use ($app, $user) {

		if (!$order = $user -> getOrder($order_id)) {
			$app -> notFound();
		}

		$content = $app -> view() -> fetch('admin_order.php',
														array(
																'order' => $order
															));
		$app -> render('admin_bar_index.php', array(
													'admin_bar_content' => $content
												));
	});

	$app -> get("/adduser", function() use ($app, $user) {

		$content = $app -> view() ->fetch('admin_add_user.php');
		$app -> render('admin_bar_index.php', array('admin_bar_content' => $content));
	});

	$app -> get("/addcategory", function() use ($app, $user) {

		$content = $app -> view() ->fetch('admin_add_category.php');
		$app -> render('admin_bar_index.php', array('admin_bar_content' => $content));
	});

	$app -> get("/addbrand", function() use ($app, $user) {

		$content = $app -> view() ->fetch('admin_add_brand.php');
		$app -> render('admin_bar_index.php', array('admin_bar_content' => $content));
	});

	$app -> get("/additem", function() use ($app, $user) {
		$brands = $user -> getBrands();
		$categories = $user -> getCategories();

		$content = $app -> view() ->fetch('admin_add_item.php',
												array(
													'categories' => $categories,
													'brands' => $brands
													)
										);
		$app -> render('admin_bar_index.php', array('admin_bar_content' => $content));
	});
});


$app -> get("/login", $authentication($app, $user, 'guest'), function() use ($app) {

	$urlRedirect_cookie = $app -> getCookie('urlRedirect');
	$urlRedirect = '/';

	if (!empty($urlRedirect_cookie)) {
		$urlRedirect = $urlRedirect_cookie;
	}
	$app -> render('login_form.php', array(
											'urlRedirect' => $urlRedirect
										));
});

$app -> get("/registration", $authentication($app, $user, 'guest'), function() use ($app) {
	$app -> render('registration_form.php');
});

$app -> get("/logout", function() use ($app, $user) {
	$user -> logout();
	$app -> redirect("/");
});


$app -> post("/login", function() use ($app, $user) {
	$email = $app->request()->post('email');
	$password = $app->request()->post('password');

	$urlRedirect_cookie = $app -> getCookie('urlRedirect');
	$urlRedirect = '/';

	if (!empty($urlRedirect_cookie)) {
		$urlRedirect = $urlRedirect_cookie;
	}

	try {
		$user -> login(array(
								'email' => $email,
								'password' => $password
							));
	} catch (Exception $e) {
		$app -> flash('error', $e -> getMessage());
		$app -> redirect("/login");
	}
	$app -> redirect($urlRedirect);
});

$app -> post("/registrate", function() use ($app, $user) {

	$params = array(
					'name' => $app -> request() -> post('name'),
					'email' => $app -> request() -> post('email'),
					'role' => $app -> request() -> post('role'),
					'password' => $app -> request() -> post('password'),
					'salt' => $app -> request() -> post('salt'),
					'iterations' => $app -> request() -> post('iterations'),
					'telephone' => $app -> request() -> post('telephone'),
					'address' => $app -> request() -> post('address')
					);

	if (!empty($params['role'])) {
		$urlRedirect = '/adminbar/adduser';
	} else {
		$urlRedirect = '/registration';
	}

	try {
		$user -> registrateNewUser($params);
	} catch (Exception $e) {
		$app -> flash('error', $e -> getMessage());
		$app -> redirect($urlRedirect);
	}
	$app -> flash('success', 'The user has been added successfully');
	$app -> redirect($urlRedirect);
});

$app -> post("/addcategory", function() use ($app, $user) {
	$category = $app -> request() -> post('category');

	$urlRedirect = '/adminbar/addcategory';

	try {
		$user -> addCategory($category);
	} catch (Exception $e) {
		$app -> flash('error', $e -> getMessage());
		$app -> redirect($urlRedirect);
	}
	$app -> flash('success', 'The category has been added successfully');
	$app -> redirect($urlRedirect);
});

$app -> post("/addbrand", function() use ($app, $user) {
	$brand = $app -> request() -> post('brand');

	$urlRedirect = '/adminbar/addbrand';
	
	try {
		$user -> addBrand($brand);
	} catch (Exception $e) {
		$app -> flash('error', $e -> getMessage());
		$app -> redirect($urlRedirect);
	}
	$app -> flash('success', 'The brand has been added successfully');
	$app -> redirect($urlRedirect);
});

$app -> post("/additem", function() use ($app, $user) {

	$urlRedirect = '/adminbar/additem';

	$image_dir = $app -> view() -> getData('image_dir');

	$params = array(
						'category' => $app -> request() -> post('category'),
						'brand' => $app -> request() -> post('brand'),
						'model' => $app -> request() -> post('model'),
						'characteristics' => $app -> request() -> post('characteristics'),
						'description' => $app -> request() -> post('description'),
						'price' => $app -> request() -> post('price'),
						'instock' => $app -> request() -> post('instock'),
						'photo' => $_FILES['file'],
						'upload_dir' => $image_dir
					);

	try {
		$user -> addItem($params);
	} catch (Exception $e) {
		$app -> flash('error', $e -> getMessage());
		$app -> redirect($urlRedirect);
	}
	$app -> flash('success', 'The item has been added successfully');
	$app -> redirect($urlRedirect);

});

$app -> post("/addtocart/:id",$access_denied($app, $user, 'admin'), function($id) use ($app, $user, $cart) {
	$cart -> addToCart($id);
	$app -> redirect($_SERVER['HTTP_REFERER']);
});

$app -> post("/delfromcart/:id",$access_denied($app, $user, 'admin'), function($id) use ($app, $cart) {
	$cart -> deleteItemFromCart($id);
	$app -> redirect($_SERVER['HTTP_REFERER']);
});

$app -> post("/checkout",$access_denied($app, $user, 'admin'), function() use ($app, $user, $cart) {
	$params = array(
					'name' => $app -> request() -> post('name'),
					'email' => $app -> request() -> post('email'),
					'telephone' => $app -> request() -> post('telephone'),
					'address' => $app -> request() -> post('address')
					);

	$urlRedirect = '/cart';

	$cart_array = $cart -> getCartArray();

	$sum = $cart -> getSum($cart_array);

	try {
		$user -> checkout($params, $cart_array, $sum);
	} catch (Exception $e) {
		$app -> flash('error', $e -> getMessage());
		$app -> redirect($urlRedirect);
	}

	$cart -> clearCart();
	$app -> flash('success', 'Your order has been received. Wait for call for confirmation.');
	$app -> redirect($urlRedirect);
});

$app -> post("/confirm/:order_id",$authentication($app, $user, 'admin'), function($order_id) use ($app, $user) {
	try {
		$user -> confirmOrder($order_id);
	} catch (Exception $e) {
		$app -> flash('error', $e -> getMessage());
		$app -> redirect($_SERVER['HTTP_REFERER']);
	}
	$app -> flash('success', 'Order has been confirmed successfully.');
	$app -> redirect($_SERVER['HTTP_REFERER']);
});

$app -> post("/delorder/:order_id",$authentication($app, $user, 'admin'), function($order_id) use ($app, $user) {

	$urlRedirect = '/adminbar/showorders';

	try {
		$user -> delOrder($order_id);
	} catch (Exception $e) {
		$app -> flash('error', $e -> getMessage());
		$app -> redirect($urlRedirect);
	}
	$app -> flash('success', 'Order has been deleted successfully.');
	$app -> redirect($urlRedirect);
});

$app -> post("/addreview", function() use ($app, $user) {

	$urlRedirect = '/reviews';

	$params = array(
					'name' => $app -> request() -> post('name'),
					'email' => $app -> request() -> post('email'),
					'review' => $app -> request() -> post('review'),
					'rating' => $app -> request() -> post('rating')
					);

	try {
		$user -> addReview($params);
	} catch (Exception $e) {
		$app -> flash('error', $e -> getMessage());
		$app -> redirect($urlRedirect);
	}
	$app -> flash('success', 'Review has been added successfully.');
	$app -> redirect($urlRedirect);
});


$app -> run();