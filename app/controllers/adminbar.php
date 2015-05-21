<?
$app->get("/adminbar", $authentication($app, $user, 'admin'), function() use ($app) {
  $app->redirect("/adminbar/showorders");
});

$app->group("/adminbar", $authentication($app, $user, 'admin'), function() use ($app, $user) {

  $app->get("/showorders(/:page(/:confirmed))", function($page = 1, $confirmed = 'all') use ($app, $user) {

    $orders_per_page = 4;//12
    

    if(($total_orders = $user->countOrders($confirmed)) === false) {
      $app->notFound();
    }

    $count_orders_all = $user->countOrders('all');

    $count_orders_confirmed = $user->countOrders('confirmed');

    $count_orders_notconfirmed = $count_orders_all - $count_orders_confirmed;
    
    $total_pages = $user->countPages($total_orders, $orders_per_page);

    if (($orders = $user->getOrders($confirmed, $total_orders, $orders_per_page, $page)) === false) {
      $app->notFound();
    }


    $content = $app->view()->fetch('admin_orders.php', array(
                                'confirmed' => $confirmed,
                                'total_pages' => $total_pages,
                                'cur_page' => $page,
                                'count_orders' => $count_orders_all,
                                'count_orders_confirmed' => $count_orders_confirmed,
                                'count_orders_notconfirmed' => $count_orders_notconfirmed,
                                'orders' => $orders
                              ));

    $app->render('admin_bar_index.php', array('admin_bar_content' => $content));

  });

  $app->get("/order/:order_id", function($order_id) use ($app, $user) {

    if (!$order = $user->getOrder($order_id)) {
      $app->notFound();
    }

    $content = $app->view()->fetch('admin_order.php',
                            array(
                                'order' => $order
                              ));
    $app->render('admin_bar_index.php', array(
                          'admin_bar_content' => $content
                        ));
  });

  $app->get("/adduser", function() use ($app, $user) {

    $content = $app->view() ->fetch('admin_add_user.php');
    $app->render('admin_bar_index.php', array('admin_bar_content' => $content));
  });

  $app->get("/addcategory", function() use ($app, $user) {

    $content = $app->view() ->fetch('admin_add_category.php');
    $app->render('admin_bar_index.php', array('admin_bar_content' => $content));
  });

  $app->get("/addbrand", function() use ($app, $user) {

    $content = $app->view() ->fetch('admin_add_brand.php');
    $app->render('admin_bar_index.php', array('admin_bar_content' => $content));
  });

  $app->get("/additem", function() use ($app, $user) {
    $brands = $user->getBrands();
    $categories = $user->getCategories();

    $content = $app->view() ->fetch('admin_add_item.php',
                        array(
                          'categories' => $categories,
                          'brands' => $brands
                          )
                    );
    $app->render('admin_bar_index.php', array('admin_bar_content' => $content));
  });

});

$app->post("/addcategory", function() use ($app, $user) {
  $category = $app->request()->post('category');

  $urlRedirect = '/adminbar/addcategory';

  try {
    $user->addCategory($category);
  } catch (Exception $e) {
    $app->flash('error', $e->getMessage());
    $app->redirect($urlRedirect);
  }
  $app->flash('success', 'The category has been added successfully');
  $app->redirect($urlRedirect);
});

$app->post("/addbrand", function() use ($app, $user) {
  $brand = $app->request()->post('brand');

  $urlRedirect = '/adminbar/addbrand';
  
  try {
    $user->addBrand($brand);
  } catch (Exception $e) {
    $app->flash('error', $e->getMessage());
    $app->redirect($urlRedirect);
  }
  $app->flash('success', 'The brand has been added successfully');
  $app->redirect($urlRedirect);
});

$app->post("/additem", function() use ($app, $user) {

  $urlRedirect = '/adminbar/additem';

  $image_dir = $app->view()->getData('image_dir');

  $params = array(
            'category' => $app->request()->post('category'),
            'brand' => $app->request()->post('brand'),
            'model' => $app->request()->post('model'),
            'characteristics' => $app->request()->post('characteristics'),
            'description' => $app->request()->post('description'),
            'price' => $app->request()->post('price'),
            'instock' => $app->request()->post('instock'),
            'photo' => $_FILES['file'],
            'upload_dir' => $image_dir
          );

  try {
    $user->addItem($params);
  } catch (Exception $e) {
    $app->flash('error', $e->getMessage());
    $app->redirect($urlRedirect);
  }
  $app->flash('success', 'The item has been added successfully');
  $app->redirect($urlRedirect);

});