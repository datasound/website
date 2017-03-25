<?php

$app->respond('GET', '/blog', function ($request, $response, $service) use ($app) {
  $posts = $app->blog->get_posts(1);
  if(empty($posts)){
    $app->abort(404);
  }
  $response->body($app->template->render(
    'blog',
    array(
        'app' => $app->config,
        'posts' => $posts,
        'page' => 1,
        'has_pagination' => $app->blog->has_pagination(1)
    )
  ));
  return $response->send();
});

$app->respond('GET', '/blog/[i:page]', function ($request, $response, $service) use ($app) {
  $page = $request->page;
  $page = $page ? (int)$page : 1;
  $posts = $app->blog->get_posts($page);
  if(empty($posts) || $page < 1){
    $app->abort(404);
  }
  return $app->template->render(
    'blog',
    array(
        'app' => $app->config,
        'posts' => $posts,
        'page' => $page,
        'has_pagination' => $app->blog->has_pagination($page)
    )
  );
});

$app->respond('GET', '/[:page]', function ($request, $response, $service) use ($app) {
  $pageName = $request->page;
  $page = $app->blog->get_page($pageName);
  if(!$page && $pageName != "blog") {
    return $app->abort(404);
  }
  return $app->template->render(
    'page',
    array(
        'app' => $app->config,
        'page' => $page
    )
  );
});

// The post page
$app->respond('GET', '/[:year]/[:month]/[:name]', function ($request, $response, $service) use ($app) {
  $post = $app->blog->find_post($request->year, $request->month, $request->name);
  if(!$post){
    $app->abort(404);
  }
  return $app->template->render(
    'post',
    array(
        'app' => $app->config,
        'title' => $post->title,
        'post' => $post
    )
  );
});
// The JSON API
$app->respond('GET', '/api/json', function ($request, $response, $service) use ($app) {
  header('Content-type: application/json');
  // Print the 10 latest posts as JSON
  return json_encode($app->blog->get_posts(1, 10));
});
// Show the RSS feed
$app->respond('GET', '/feed/rss', function ($request, $response, $service) use ($app) {
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
