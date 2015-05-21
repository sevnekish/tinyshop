<?
$app->get("/reviews(/:page)", function($page = 1) use ($app, $user) {
  $reviews_per_page = 6;

  if(!$total_reviews = $user->countReviews()) {
    $app->notFound();
  }

  $total_pages = $user->countPages($total_reviews, $reviews_per_page);

  if (!$reviews = $user->getReviews($total_reviews, $reviews_per_page, $page)) {
    $app->notFound();
  }

  $user_info = $user->getInfo();
  $app->render('reviews.php', array(
                    'user_info' => $user_info,
                    'total_pages' => $total_pages,
                    'cur_page' => $page,
                    'reviews' => $reviews
                  ));
});

$app->post("/addreview", function() use ($app, $user) {

  $urlRedirect = '/reviews';

  $params = array(
          'name' => $app->request()->post('name'),
          'email' => $app->request()->post('email'),
          'review' => $app->request()->post('review'),
          'rating' => $app->request()->post('rating')
          );

  try {
    $user->addReview($params);
  } catch (Exception $e) {
    $app->flash('error', $e->getMessage());
    $app->redirect($urlRedirect);
  }
  $app->flash('success', 'Review has been added successfully.');
  $app->redirect($urlRedirect);
});

$app->post("/delreview/:review_id", $authentication($app, $user, 'admin'), function($review_id) use ($app, $user) {

  $urlRedirect = '/reviews';

  

  try {
    $user->deleteReview($review_id);
  } catch (Exception $e) {
    $app->flash('error', $e->getMessage());
    $app->redirect($urlRedirect);
  }
  $app->flash('success', 'Review has been deleted successfully.');
  $app->redirect($urlRedirect);
});