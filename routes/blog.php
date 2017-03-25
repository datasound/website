<?php

$container = $app->getContainer();
$container['view'] = new \Slim\Views\Handlebars(
   'public/themes/'. $config['theme'] .'/views'
);

$app->get('/blog', function ($request, $response, $args) use ($app){
  $posts = $app->blog->get_posts(1);
  if(empty($posts)){
    $app->abort(404);
  }

  return $this->view->render(
    $response,
    'blog',
    array(
      'app' => $app->config,
      'posts' => $posts,
      'page' => 1,
      'has_pagination' => $app->blog->has_pagination(1)
    )
  );
});

// The post page
$app->get('/{year}/{month}/{name}', function ($request, $response, $args) use ($app) {
  $post = $app->blog->find_post($args['year'], $args['month'], $args['name']);
  if(!$post){
    $app->abort(404);
  }
  return $this->view->render(
    $response,
    'post',
    array(
        'app' => $app->config,
        'title' => $post->title,
        'post' => $post
    )
  );
});

// The JSON API
$app->get('/api/json', function ($request, $response, $args) use ($app) {
  header('Content-type: application/json');
  // Print the 10 latest posts as JSON
  return $response->getBody()->write(json_encode($app->blog->get_posts(1, 10)));
});
// Show the RSS feed
$app->get('/feed/rss', function ($request, $response, $args) use ($app) {
  header('Content-Type: application/rss+xml');
  $url = $app->config['url'];
  $title = $app->config['title'];
  $description = $app->config['description'];
  $feed = new Feed();
  $channel = new Channel();
  $channel
    ->title($title)
    ->description($description)
    ->url($url)
    ->appendTo($feed);
  // the latest 30 posts
  $posts = $app->blog->get_posts(1, 30);
  foreach($posts as $p){
    $item = new Item();
    $item
      ->title($p->title)
      ->description($p->body)
      ->url($p->url)
      ->appendTo($channel);
  }
  return $feed;
});

?>
