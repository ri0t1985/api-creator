<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Symfony\Component\HttpFoundation\Request;

date_default_timezone_set('Europe/Amsterdam');

//accepting JSON
/** @var Silex\Application $app */
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

$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/."), $isDevMode);

// obtaining the entity manager
$app['entity.manager'] = EntityManager::create($app["db.options"], $config);

//load services
$servicesLoader = new App\ServicesLoader($app);
$servicesLoader->bindServicesIntoContainer();

//load routes
$routesLoader = new App\RoutesLoader($app);
$routesLoader->bindRoutesToControllers();

return $app;