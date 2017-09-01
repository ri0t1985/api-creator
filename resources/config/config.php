<?php

$app['api.version']  = "v1";
$app['api.endpoint'] = "/api";


$app['log.level']    = Monolog\Logger::ERROR;

$app['html.service'] = \App\SourceRetrieval\Curl::class;

/**
 * Cache
 */
$app['cache.class'] = \App\Cache\Redis::class;
$app['cache.options'] = [
    "scheme" => "tcp",
    "host"   => "127.0.0.1",
    "port"   => 6379,
];

/**
* MySQL
*/
$app['db.options'] = array(
  "driver"   => "pdo_mysql",
  "user"     => "root",
  "password" => "root",
  "dbname"   => "api",
  "host"     => "db",
);


if (file_exists(__DIR__.'/config.local.php'))
{
    include(__DIR__.'/config.local.php');
}