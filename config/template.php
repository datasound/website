<?php
// setting globals
$container_config['view']['app'] = $config;
// adding some helper to handlebars
$container_config['view']->addHelper("striptags",
                        function($template, $context, $args, $source){
                            return strip_tags($context->get($args));
});
$container_config['view']->addHelper("urlencode",
                        function($template, $context, $args, $source){
                            return urlencode($context->get($args));
});
$container_config['view']->addHelper("urldecode",
                        function($template, $context, $args, $source){
                            return urldecode($context->get($args));
});
$container_config['view']->addHelper("formatDate",
                        function($template, $context, $args, $source){
                          preg_match("/(.*?)\s+(?:(?:\"|\')(.*?)(?:\"|\'))/", $args, $m);
                          $keyname = $m[1];
                          $format = $m[2];

                          $date = $context->get($keyname);
                          $localized_date = "";
                          if ($format && is_numeric($date)) {
                            $localized_date = strftime($format, $date);
                          }
                          return $localized_date;
});