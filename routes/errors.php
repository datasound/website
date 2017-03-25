<?php
$container = $app->getContainer();
$container['view'] = new \Slim\Views\Handlebars(
   'public/themes/'. $config['theme'] .'/views'
);

// Generic error
$app->get('/error/i-am-so-sorry', function ($request, $response, $args) use ($app) {
  return $this->view->render(
    $response,
    '500',
    array(
        'app' => $app->config
    )
  );
});
// 404 not found
$container['notFoundHandler'] = function ($c) use ($app){
  return function ($request, $response, $args) use ($c, $app) {
      return $c['view']->render(
        $response,
        '404',
        array(
            'app' => $app->config
        )
      );
    };
};

// error 500
$c = $app->getContainer();
$c['phpErrorHandler'] = function ($c) use ($app){
    return function ($request, $response, $error) use ($c, $app) {
      return $c['view']->render(
        $response,
        '500',
        array(
            'app' => $app->config
        )
      );
    };
};

?>
