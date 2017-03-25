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
  $config = Yaml::parse(file_get_contents(("./config/" . ((getenv("ENV") == 'development') ? 'development' : 'production') .".yaml")), true);
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
$app = new \Slim\App(array(
  'settings' => [
        'displayErrorDetails' => true
    ]
));
date_default_timezone_set($config['timezone']);
setlocale(LC_ALL, $config['locale']);

include("./config/template.php");
# Init the blog engine
$app->blog = new Manager($config);
# Register $config array into the $app
$app->config = $config;

include("./routes/redirects.php");
include("./routes/home.php");
include("./routes/blog.php");
include("./routes/errors.php");
$app->run();
