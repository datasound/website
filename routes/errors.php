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
// Override the default Slim Not Found Handler
$container['notFoundHandler'] = function ($c){
  return function ($request, $response, $args) use ($c) {
      return $c['view']->render(
        $response,
        '404',
        array(
            'app' => $app->config
        )
      );
    };
};
?>
