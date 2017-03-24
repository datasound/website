<?php
$container = $app->getContainer();
$container['view'] = new \Slim\Views\Handlebars(
   'public/themes/'. $config['theme'] .'/views'
);

$app->get('/', function ($request, $response, $args) use ($app){

    return $this->view->render(
      $response,
      'home',
      array(
          'app' => $app->config,
          'title' => 'Home'
      )
  );
});
