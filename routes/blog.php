<?php
$app->get('/blog', function ($request, $response, $args) use ($app, $container){
  $posts = $app->blog->get_posts(1);
  if (empty($posts)) {
    $handler = $this->notFoundHandler;
    return $handler($request, $response);
  }
  # strip tags from post->body
  $posts = array_map(function($post) {
    $post->body = strip_tags($post->body);
    return $post;
  }, $posts);
  return $this->view->render(
    $response,
    'blog',
    array(
      'posts' => $posts,
      'page' => 1,
      'has_pagination' => $app->blog->has_pagination(1)
    )
  );
});

// The post page
$app->get('/{year}/{month}/{name}', function ($request, $response, $args) use ($app, $container) {
  $post = $app->blog->find_post($args['year'], $args['month'], $args['name']);
  if (!$post) {
    $handler = $this->notFoundHandler;
    return $handler($request, $response);
  }
  return $this->view->render(
    $response,
    'post',
    array(
        'title' => $post->title,
        'post' => $post
    )
  );
});

$app->get('/blog/{i:page}', function ($request, $response, $service) use ($app) {
  $page = $args['page'];
  $page = $page ? (int) $page : 1;
  $posts = $app->blog->get_posts($page);
  if (empty($posts) || $page < 1) {
    $handler = $this->notFoundHandler;
    return $handler($request, $response);
  }
  return $this->view->render(
    'blog',
    array(
        'posts' => $posts,
        'page' => $page,
        'has_pagination' => $app->blog->has_pagination($page)
    )
  );
});

$app->get('/{:page}', function ($request, $response, $service) use ($app) {
  $pageName = $request->page;
  $page = $app->blog->get_page($pageName);
  if (!$page && $pageName != "blog") {
    $handler = $this->notFoundHandler;
    return $handler($request, $response);
  }
  return $app->template->render(
    'page',
    array(
        'page' => $page
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
  $url = $app->config['url'];
  $title = $app->config['title'];
  $description = $app->config['description'];
  $feed = new \Suin\RSSWriter\Feed();
  $channel = new \Suin\RSSWriter\Channel();
  $channel
    ->title($title)
    ->description($description)
    ->url($url)
    ->appendTo($feed);
  // the latest 30 posts
  $posts = $app->blog->get_posts(1, 30);
  foreach($posts as $p){
    $item = new \Suin\RSSWriter\Item();
    $item
      ->title($p->title)
      ->description($p->body)
      ->url($p->url)
      ->appendTo($channel);
  }
  $body = $response->getBody();
  $body->write($feed->render());
  return $response->withHeader('Content-Type', 'application/rss+xml');
});