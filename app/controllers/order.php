<?
$app->post("/confirm/:order_id",$authentication($app, $user, 'admin'), function($order_id) use ($app, $user) {
  try {
    $user->confirmOrder($order_id);
  } catch (Exception $e) {
    $app->flash('error', $e->getMessage());
    $app->redirect($_SERVER['HTTP_REFERER']);
  }
  $app->flash('success', 'Order has been confirmed successfully.');
  $app->redirect($_SERVER['HTTP_REFERER']);
});

$app->post("/delorder/:order_id",$authentication($app, $user, 'admin'), function($order_id) use ($app, $user) {

  $urlRedirect = '/adminbar/showorders';

  try {
    $user->delOrder($order_id);
  } catch (Exception $e) {
    $app->flash('error', $e->getMessage());
    $app->redirect($urlRedirect);
  }
  $app->flash('success', 'Order has been deleted successfully.');
  $app->redirect($urlRedirect);
});