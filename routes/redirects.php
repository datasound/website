<?php
$app->get('/slack', function ($request, $response) {
    return $response->withRedirect('https://datasounds.slack.com', 302);
});
