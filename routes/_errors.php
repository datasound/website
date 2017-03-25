<?php
// Generic error
$app->respond('GET', '/error/i-am-so-sorry', function ($request, $response, $service) use ($app) {
  $app->abort(500);
});

// Using range behaviors via if/else
$app->onHttpError(function ($code, $router) use ($app) {
    if ($code >= 400 && $code < 500) {
      $router->response()->body($app->template->render(
        '404',
        array(
            'app' => $app->config
        )
      ))->send();
    } elseif ($code >= 500 && $code <= 599) {
      $router->response()->body($app->template->render(
        '500',
        array(
            'app' => $app->config
        )
      ));
    }
});

 ?>
