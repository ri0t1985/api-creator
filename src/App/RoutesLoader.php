<?php

namespace App;

use App\Controllers\DefaultController;
use App\Services\DatabaseServiceContainer;
use App\Services\EndPointService;
use App\Services\WebsiteService;
use Silex\Application;

class RoutesLoader
{
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function bindRoutesToControllers()
    {
        /** @var DatabaseServiceContainer $databaseServiceContainer */
        $databaseServiceContainer = $this->app['database.service_container'];

        $websites = $databaseServiceContainer->getWebsiteService()->getAll();
        $api = $this->app["controllers_factory"];

        foreach ($websites as $website)
        {
            $endpoints = $databaseServiceContainer->getEndPointService()->getAll();
            foreach ($endpoints as $endpoint)
            {
                $api->get('/'.$website['name'].'/'.$endpoint['name'], function () use ($databaseServiceContainer, $website, $endpoint) {
                    $controller = new DefaultController($databaseServiceContainer);
                    return $controller->processEndPoint($website, $endpoint);
                });

                $api->get('/'.$website['name'].'/'.$endpoint['name'].'/search/{key}/{value}', function ($key, $value) use ($databaseServiceContainer, $website, $endpoint) {
                    $controller = new DefaultController($databaseServiceContainer);
                    return $controller->search($website, $endpoint, $key, $value);
                });
            }
        }

        $this->app->mount($this->app["api.endpoint"].'/'.$this->app["api.version"], $api);
    }
}

