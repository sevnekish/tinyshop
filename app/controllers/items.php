<?
$app->get("/item/:id", function($id) use ($app, $user) {

  if (!$item = $user->getItem($id)) {
    $app->notFound();
  }

  $app->render('item.php', array(
                    'item' => $item
                  ));
});