<?
$app->get("/cart",$access_denied($app, $user, 'admin'), function() use ($app, $cart, $user) {

  $cart_array = $cart->getCartArray();

  $sum = $cart->getSum($cart_array);

  $app->render('shopping_cart.php', array(
                    'cart_array' => $cart_array,
                    'sum' => $sum
                  ));
});

$app->post("/addtocart/:id",$access_denied($app, $user, 'admin'), function($id) use ($app, $user, $cart) {
  $cart->addToCart($id);
  $app->redirect($_SERVER['HTTP_REFERER']);
});

$app->post("/delfromcart/:id",$access_denied($app, $user, 'admin'), function($id) use ($app, $cart) {
  $cart->deleteItemFromCart($id);
  $app->redirect($_SERVER['HTTP_REFERER']);
});

$app->get("/checkout",$access_denied($app, $user, 'admin'), function() use ($app, $user, $cart) {

  $cart_count = $app->view()->getData('cart_count');

  if (!($cart_count > 0)) {
    $app->redirect('/cart');
  }

  $user_role = $user->getRole();

  $user_info = array();

  if ($user_role == 'user') {
    $user_info = $user->getInfo();
  }

  $app->render('checkout.php', array(
                    'user_info' => $user_info
                  ));
});

$app->post("/checkout",$access_denied($app, $user, 'admin'), function() use ($app, $user, $cart) {
  $params = array(
          'name' => $app->request()->post('name'),
          'email' => $app->request()->post('email'),
          'telephone' => $app->request()->post('telephone'),
          'address' => $app->request()->post('address')
          );

  $urlSuccess = '/cart';
  $urlError = '/checkout';

  $cart_array = $cart->getCartArray();

  $sum = $cart->getSum($cart_array);

  try {
    $user->checkout($params, $cart_array, $sum);
  } catch (Exception $e) {
    $app->flash('error', $e->getMessage());
    $app->redirect($urlError);
  }

  $cart->clearCart();
  $app->flash('success', 'Your order has been received. Wait for call for confirmation.');
  $app->redirect($urlSuccess);
});