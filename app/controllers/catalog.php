<?
$app->get("/catalog", function() use ($app, $user) {

  $items = $user->getItemsRandom(12);

  $app->render('catalog_main.php', array(
                      'items' => $items
                    ));
});

$app->get("/catalog/:category(/:page(/:sort))", function($category, $page = 1, $sort = 'cheap')
                          use ($app, $user) {

  $items_per_page = 12;
  
  if(!$category_id = Validator::find_matches('categories', 'category', $category)) {
    $app->notFound();
  }

  if(!$total_items = $user->countItems($category_id)) {
    $app->notFound();
  }

  $total_pages = $user->countPages($total_items, $items_per_page);

  if (!$items = $user->getItems($category_id, $sort, $total_items, $items_per_page, $page)) {
    $app->notFound();
  }

  $app->render('catalog.php', array(
                    'cur_category' => $category,
                    'cur_sort' => $sort,
                    'total_pages' => $total_pages,
                    'cur_page' => $page,
                    'items' => $items
                  ));
});