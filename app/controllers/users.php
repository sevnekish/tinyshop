<?
$app->get("/logreg", function() use ($app) {
  $app->redirect("/");
});

$app->get("/login", $authentication($app, $user, 'guest'), function() use ($app) {

  $urlRedirect_cookie = $app->getCookie('urlRedirect');
  $urlRedirect = '/';

  if (!empty($urlRedirect_cookie)) {
    $urlRedirect = $urlRedirect_cookie;
  }
  $app->render('login_form.php', array(
                      'urlRedirect' => $urlRedirect
                    ));
});

$app->get("/registration", $authentication($app, $user, 'guest'), function() use ($app) {
  $app->render('registration_form.php');
});

$app->get("/logout", function() use ($app, $user) {
  $user->logout();
  $app->redirect("/");
});


$app->post("/login", function() use ($app, $user) {
  $email = $app->request()->post('email');
  $password = $app->request()->post('password');

  $urlRedirect_cookie = $app->getCookie('urlRedirect');
  $urlRedirect = '/';

  if (!empty($urlRedirect_cookie)) {
    $urlRedirect = $urlRedirect_cookie;
  }

  try {
    $user->login(array(
                'email' => $email,
                'password' => $password
              ));
  } catch (Exception $e) {
    $app->flash('error', $e->getMessage());
    $app->redirect("/login");
  }
  $app->redirect($urlRedirect);
});

$app->post("/registrate", function() use ($app, $user) {

  $params = array(
          'name' => $app->request()->post('name'),
          'email' => $app->request()->post('email'),
          'role' => $app->request()->post('role'),
          'password' => $app->request()->post('password'),
          'salt' => $app->request()->post('salt'),
          'iterations' => $app->request()->post('iterations'),
          'telephone' => $app->request()->post('telephone'),
          'address' => $app->request()->post('address')
          );

  if (!empty($params['role'])) {
    $urlRedirect = '/adminbar/adduser';
  } else {
    $urlRedirect = '/registration';
  }

  try {
    $user->registrateNewUser($params);
  } catch (Exception $e) {
    $app->flash('error', $e->getMessage());
    $app->redirect($urlRedirect);
  }
  $app->flash('success', 'The user has been added successfully');
  $app->redirect($urlRedirect);
});