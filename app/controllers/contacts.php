<?
$app->get("/contacts", function() use ($app) {
  $app->render('contacts.php');
});