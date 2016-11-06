<?php
use Handlebars\Handlebars;
use Handlebars\Loader\FilesystemLoader;

$tplDir = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . $config['theme'] . DIRECTORY_SEPARATOR . "views";

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
$app->template->addHelper("urlencode",
                        function($template, $context, $args, $source){
                            return urlencode($context->get($args));
});
$app->template->addHelper("urldecode",
                        function($template, $context, $args, $source){
                            return urldecode($context->get($args));
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

 ?>
