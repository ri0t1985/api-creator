<?php

use Silex\Application;
use Silex\Provider\HttpCacheServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\ServicesLoader;
use App\RoutesLoader;
use Carbon\Carbon;

date_default_timezone_set('Europe/Amsterdam');

//accepting JSON
$app->before(function (Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});

$app->register(new ServiceControllerServiceProvider());

$app->register(new DoctrineServiceProvider(), array(
    "db.options" => $app["db.options"]
));


$app->register(new MonologServiceProvider(), array(
    "monolog.logfile" => ROOT_PATH . "/storage/logs/dev.log",
    "monolog.level" => $app["log.level"],
    "monolog.name" => "application"
));

//load services
$servicesLoader = new App\ServicesLoader($app);
$servicesLoader->bindServicesIntoContainer();


//load routes
$routesLoader = new App\RoutesLoader($app);
$routesLoader->bindRoutesToControllers();


return $app;