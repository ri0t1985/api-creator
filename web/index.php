<?php
if(file_exists('_intellij_phpdebug_validator.php')) {
    include_once('_intellij_phpdebug_validator.php');
    die;
}

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();


require __DIR__ . '/../resources/config/config.php';

require __DIR__ . '/../src/app.php';

$app->run();