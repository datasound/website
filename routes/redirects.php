<?php
// path slack
$app->get('/slack', function ($request, $response, $args) {
    return $response->withRedirect('https://datasounds.slack.com', 302);
});
/*$app->respond('GET', '/slack', function ($request, $response, $service) use ($app) {
  $response->redirect('https://datasounds.slack.com', 302)->send();
});*/
