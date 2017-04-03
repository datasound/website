<?php
require_once 'vendor/autoload.php';
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Blog\Manager;
use Symfony\Component\Yaml\Yaml;

function error_handler($error) {
  header("Location: /error/i-am-so-sorry");
}

if(getenv("ENV") === "development") {
  error_reporting(~E_NOTICE);
} else {
  set_error_handler('error_handler');
  error_reporting(~E_ALL);
}
try {
  # Getting configuration from config.json
  $config = Yaml::parse(file_get_contents(("./config/" . ((getenv("ENV") === 'development') ? 'development' : 'production') .".yaml")), true);
} catch(\Exception $e) {
  die($e->getMessage());
  exit;
}
// needed when installed into subdirectory
// check https://github.com/chriso/klein.php/wiki/Sub-Directory-Installation
// $base  = dirname($_SERVER['PHP_SELF']);
// if(ltrim($base, '/')){
//    $_SERVER['REQUEST_URI'] = substr($_SERVER['REQUEST_URI'], strlen($base));
// }

$container_config = [];
$container_config['settings'] = [
  'displayErrorDetails' => (getenv("ENV") === 'development') ? true : false
];
include("./config/errors.php");
$container_config['view'] = new \Slim\Views\Handlebars('public/themes/'. $config['theme'] .'/views');
# set up the template engine with some awesome helpers and globals
include("./config/template.php");
# Instantiating the app
$app = new \Slim\App($container_config);
date_default_timezone_set($config['timezone']);
setlocale(LC_ALL, $config['locale']);
# Init the blog engine
$app->blog = new Manager($config);
# Register $config array into the $app
$app->config = $config;

include("./routes/redirects.php");
include("./routes/home.php");
include("./routes/blog.php");

$app->run();
