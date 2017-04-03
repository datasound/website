<?php
// 404 not found
$container_config['notFoundHandler'] = function ($c) {
  return function ($request, $response) use ($c) {
      return $c['view']->render($response->withStatus(404), '404', []);
    };
};

// error 500
$container_config['errorHandler'] = function ($c) {
    return function ($request, $response, $error) use ($c) {
      return $c['view']->render($response->withStatus(500), '500', []);
    };
};