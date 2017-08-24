<?php

$app['api.version']  = "v1";
$app['api.endpoint'] = "/api";


$app['log.level'] = Monolog\Logger::ERROR;


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