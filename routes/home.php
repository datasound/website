<?php
$app->get('/', function ($request, $response, $args) use ($app){
    return $this->view->render($response, 'home', ['title' => 'Home']);
});
