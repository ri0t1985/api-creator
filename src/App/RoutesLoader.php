<?php

namespace App;

use App\Controllers\DefaultController;
use App\Controllers\RequestController;
use App\Services\DatabaseServiceContainer;
use App\Services\EndPointService;
use App\Services\WebsiteService;
use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

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

        $api->post('/create',function(Request $request) use ($databaseServiceContainer){
            $controller = new RequestController($databaseServiceContainer);
            return $controller->create($request);
        });

        $api->put('/update/{id}',function(Request $request, $id) use ($databaseServiceContainer){
            $controller = new RequestController($databaseServiceContainer);
            return $controller->update($request, $id);
        });

        $api->delete('/delete/{websiteName}/{endpointName}',function($websiteName, $endpointName) use ($databaseServiceContainer){
            $controller = new RequestController($databaseServiceContainer);
            return $controller->delete($websiteName, $endpointName);
        });

        $api->match('{url}', function(){
            return new JsonResponse(['The requested end point does not exit', 404]);
        })->assert('url', '.+');

        $this->app->mount($this->app["api.endpoint"].'/'.$this->app["api.version"], $api);
    }
}

