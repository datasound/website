<?php
require_once 'vendor/autoload.php';
use KISSBlog\BlogManager;
use KISSBlog\Utils;
use Handlebars\Handlebars;
use Handlebars\Loader\FilesystemLoader;
use Suin\RSSWriter\Feed;
use Suin\RSSWriter\Channel;
use Suin\RSSWriter\Item;
use Symfony\Component\Yaml\Yaml;

# putenv("env=production");

function error_handler($error) {
  header("Location: /i-am-so-sorry");
}

if(getenv("env")=="production") {
  set_error_handler('error_handler');
  error_reporting(~E_ALL);
} else {
  error_reporting(~E_NOTICE);
}

try {
  # Getting configuration from config.json
  $config = Yaml::parse(file_get_contents((getenv("env") == 'production') ? 'production' : 'development'.".yaml"), true);
} catch(\Exception $e) {
  die($e->getMessage());
  exit;
}

// needed when installed into subdirectory
// check https://github.com/chriso/klein.php/wiki/Sub-Directory-Installation
$base  = dirname($_SERVER['PHP_SELF']);
if(ltrim($base, '/')){
    $_SERVER['REQUEST_URI'] = substr($_SERVER['REQUEST_URI'], strlen($base));
}

# Declaring the $app
$app = new \Klein\Klein();
date_default_timezone_set($config['timezone']);
setlocale(LC_ALL, $config['locale']);

#################################
# START TEMPLATE INITIALIZATION #
#################################
$tplDir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . $config['theme'] . DIRECTORY_SEPARATOR . "views";

$handlebarsLoader = new FilesystemLoader($tplDir, [
    "extension" => "html"
]);
// Set the partials files
$partialsDir = $tplDir . DIRECTORY_SEPARATOR . 'partials' . DIRECTORY_SEPARATOR;

$partialsLoader = new FilesystemLoader($partialsDir, [
    "extension" => "html"
]);
$app->template = new Handlebars([
    "loader" => $handlebarsLoader,
    "partials_loader" => $partialsLoader
]);
// adding some helper to handlebars
$app->template->addHelper("striptags",
                        function($template, $context, $args, $source){
                            return strip_tags($context->get($args));
});
$app->template->addHelper("excerpt",
                        function($template, $context, $args, $source){
                          preg_match("/(.*?)\s+(.*?)\s+(?:(?:\"|\')(.*?)(?:\"|\'))/", trim($args), $m);
                          $keyname = $m[1];
                          $limit = $m[2];
                          $ellipsis = $m[3];
                          $varContent = strip_tags($context->get($keyname));
                          $words = str_word_count($varContent, 2);
                          $value = "";
                          if(count($words) > $limit) {
                            $permitted = array_slice($words, 0, $limit, true);
                            end($permitted);
                            $lastWordPosition = key($permitted);
                            $lastWord = $permitted[$lastWordPosition];
                            $lastWordLength = strlen($lastWord);
                            $realLimit = $lastWordPosition+$lastWordLength;
                            $value = substr($varContent, 0, $realLimit);
                          } else {
                            $value .= $varContent;
                          }
                          if ($ellipsis) {
                              $value .= $ellipsis;
                          }
                          return $value;
});
$app->template->addHelper("format_date_with_locale",
                        function($template, $context, $args, $source){
                          preg_match("/(.*?)\s+(?:(?:\"|\')(.*?)(?:\"|\'))/", $args, $m);
                          $keyname = $m[1];
                          $format = $m[2];

                          $date = $context->get($keyname);
                          $localized_date = "bad format";
                          if ($format && is_numeric($date)) {
                            $localized_date = strftime($format, $date);
                          }
                          return $localized_date;
});
###############################
# END TEMPLATE INITIALIZATION #
###############################

# Init the blog engine
$app->blog = new BlogManager($config);

# Register $config array into the $app
$app->config = $config;

###############################
## START ROUTES DECLARATION  ##
###############################
$app->respond('GET', '/', function ($request, $response, $service) use ($app) {
  $posts = $app->blog->get_posts(1);
  if(empty($posts)){
    $app->abort(404);
  }
  echo $app->template->render(
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
  echo $app->template->render(
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
  echo $app->template->render(
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
  echo $app->template->render(
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
  echo json_encode($app->blog->get_posts(1, 10));
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
  echo $feed;
});


// errors
// Generic error
$app->respond('GET', '/i-am-so-sorry', function ($request, $response, $service) use ($app) {
  $app->abort(500);
});

// Using range behaviors via if/else
$app->onHttpError(function ($code, $router) use ($app) {
    if ($code >= 400 && $code < 500) {
      echo $app->template->render(
        '404',
        array(
            'blog' => $app->config
        )
      );
    } elseif ($code >= 500 && $code <= 599) {
      echo $app->template->render(
        '500',
        array(
            'blog' => $app->config
        )
      );
    }
});
###############################
### END ROUTES DECLARATION  ###
###############################

# Start the $app;
$app->dispatch();