<?
$app->get("/", function() use ($app, $user) {

  $items = $user->getItemsRandom(6);

  $app->render('home.php', array('items' => $items));
});