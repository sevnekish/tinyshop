<?
require '../vendor/autoload.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
// $app->add(new \Slim\Middleware\SessionCookie()); not work well, beacause we use $_SESSION => session_start();
session_start();


$app->config(array('templates.path' => '../app/views'));

$app->notFound(function () use ($app) {
  $app->render('404.php');
});

$image_dir = 'content/images/catalog/';

$user = UsersFactory::createUser();

$cart = new ShoppingCart();


$authentication = function($app, $user, $authentication_role) {
  return function() use ($app, $user, $authentication_role) {
    $user_role = $user->getRole();
    if ($authentication_role != $user_role) {
      switch($user_role){
        case 'admin': $app->redirect("/adminbar"); break;
        case 'user': $app->redirect("/account"); break;
        case 'guest': 
          $app->flash('error', 'Login required');
          $app->setCookie('urlRedirect', $app->request()->getPathInfo(), '4 minutes');
          $app->redirect("/login");
          break;
        }
    }
  };
};

$access_denied = function($app, $user, $denied_user_role) {
  return function() use ($app, $user, $denied_user_role) {
    $user_role = $user->getRole();
    if ($denied_user_role == $user_role) {
      switch($denied_user_role){
        case 'admin': $app->redirect("/adminbar"); break;
        case 'user': $app->redirect("/account"); break;
        case 'guest': 
          $app->flash('error', 'Login required');
          $app->setCookie('urlRedirect', $app->request()->getPathInfo(), '4 minutes');
          $app->redirect("/login");
          break;
        }
    }
  };
};


$app->hook('slim.before.dispatch', function() use ($app, $user, $cart, $image_dir) {
  $userparams = $user->getParams();
  $categories = $user->getCategories();
  $cart_count = $cart->getCount();

  $flash = $app->view()->getData('flash');
  $error = '';
  $success = '';
  if (isset($flash['error'])) {
    $error = $flash['error'];
  }
  if (isset($flash['success'])) {
    $success = $flash['success'];
  }

  $app->view()->setData(array(
                  'userparams' => $userparams,
                  'cart_count' => $cart_count,
                  'image_dir' => $image_dir,
                  'error' => $error,
                  'success' => $success
                ));
  $app->render('header.php', array('categories' => $categories));
});

$app->hook('slim.after.dispatch', function() use ($app, $user) {
  $app->render('footer.php');
});

require_once 'route.php';


$app->run();