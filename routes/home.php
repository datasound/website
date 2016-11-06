<?php

$app->respond('GET', '/', function ($request, $response, $service) use ($app) {
  return $app->template->render(
    'home',
    array(
        'app' => $app->config,
        'title' => "Home"
    )
  );
});

?>
