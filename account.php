<?
$app->get("/account", $authentication($app, $user, 'user'), function() use ($app) {
  $app->redirect("/account/userinfo");
});

$app->group("/account", $authentication($app, $user, 'user'), function() use ($app, $user) {

  $app->get("/userinfo", function() use ($app, $user) {

    $user_info = $user->getInfo();

    $content = $app->view()->fetch('account_userinfo.php',
                            array(
                                'user_info' => $user_info
                              ));
    $app->render('account_index.php', array(
                          'account_content' => $content
                        ));
  });


  $app->get("/showorders", function() use ($app, $user) {

    $user_orders = $user->getOrders();

    $count_orders = $user->countOrders();

    $count_orders_confirmed = $user->countOrders('confirmed');

    $count_orders_notconfirmed = $count_orders - $count_orders_confirmed;

    $content = $app->view()->fetch('account_orders.php',
                            array(
                                'user_orders' => $user_orders,
                                'count_orders' => $count_orders,
                                'count_orders_confirmed' => $count_orders_confirmed,
                                'count_orders_notconfirmed' => $count_orders_notconfirmed
                              ));
    $app->render('account_index.php', array(
                          'account_content' => $content
                        ));
  });

  $app->get("/order/:order_id", function($order_id) use ($app, $user) {

    if (!$user_order = $user->getOrder($order_id)) {
      $app->notFound();
    }

    $content = $app->view()->fetch('account_order.php',
                            array(
                                'user_order' => $user_order
                              ));
    $app->render('account_index.php', array(
                          'account_content' => $content
                        ));
  });
});