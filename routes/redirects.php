<?php
$app->get('/slack', function ($request, $response, $args) {
    return $response->withRedirect('https://datasounds.slack.com', 302);
});
