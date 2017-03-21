<?php
$c = new \Slim\Container(); //Create Your container
// Using range behaviors via if/else
$c['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
       if ($c['response']->getStatusCode() >= 400 && $c['response']->getStatusCode() < 500) {
          return $c['response']
              ->withHeader('Content-Type', 'text/html')
              ->write("Client error");
        } else if ($c['response']->getStatusCode() >= 500 && $c['response']->getStatusCode() <= 599) {
          return $c['response']
              ->withHeader('Content-Type', 'text/html')
              ->write("Server error");
        }
    };
};

$app = new \Slim\App($c);
$app->run();

// errors
// Generic error
/*$app->respond('GET', '/error/i-am-so-sorry', function ($request, $response, $service) use ($app) {
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
