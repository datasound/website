<?php

$app->respond('GET', '/blog', function ($request, $response, $service) use ($app) {
  $posts = $app->blog->get_posts(1);
  if(empty($posts)){
    $app->abort(404);
  }
  return $app->template->render(
    'main',
    array(
        'blog' => $app->config,
        'posts' => $posts,
        'page' => 1,
        'has_pagination' => $app->blog->has_pagination(1)
    )
  );
});
$app->respond('GET', '/page/[i:page]', function ($request, $response, $service) use ($app) {
  $page = $request->page;
  $page = $page ? (int)$page : 1;
  $posts = $app->blog->get_posts($page);
  if(empty($posts) || $page < 1){
    $app->abort(404);
  }
  return $app->template->render(
    'main',
    array(
        'blog' => $app->config,
        'posts' => $posts,
        'page' => $page,
        'has_pagination' => $app->blog->has_pagination($page)
    )
  );
});
$app->respond('GET', '/[:page]', function ($request, $response, $service) use ($app) {
  $pageName = $request->page;
  $page = $app->blog->get_page($pageName);
  if(!$page) {
    $app->abort(404);
  }
  return $app->template->render(
    'page',
    array(
        'blog' => $app->config,
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
        'blog' => $app->config,
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


// errors
// Generic error
$app->respond('GET', '/i-am-so-sorry', function ($request, $response, $service) use ($app) {
  $app->abort(500);
});

// Using range behaviors via if/else
$app->onHttpError(function ($code, $router) use ($app) {
    if ($code >= 400 && $code < 500) {
      return $app->template->render(
        '404',
        array(
            'blog' => $app->config
        )
      );
    } elseif ($code >= 500 && $code <= 599) {
      return $app->template->render(
        '500',
        array(
            'blog' => $app->config
        )
      );
    }
});
