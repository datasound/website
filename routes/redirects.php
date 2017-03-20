<?php

$app->respond('GET', '/slack', function ($request, $response, $service) use ($app) {
  $response->redirect('https://datasounds.slack.com', 302)->send();
});