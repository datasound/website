<?php
$app = new \Slim\App();
$app->get('/', function ($request, $response, $args) use ($app){
  return $app->render(
    'home',
    array(
        'app' => $app->config,
        'title' => "Home"
    ),
  );
});
$app->run();
/*$app->respond('GET', '/', function ($request, $response, $service) use ($app) {
  return $app->template->render(
    'home',
    array(
        'app' => $app->config,
        'title' => "Home"
    )
  );
});*/
