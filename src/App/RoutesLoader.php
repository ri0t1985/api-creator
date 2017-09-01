<?php

namespace App;

use App\Controllers\DefaultController;
use App\Controllers\RequestController;
use App\Exceptions\ConfigurationException;
use App\Services\DatabaseServiceContainer;
use App\SourceRetrieval\SourceRetrievalInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RoutesLoader
{
    private $app;

    /**
     * RoutesLoader constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Binds the routes to the controller
     */
    public function bindRoutesToControllers()
    {
        var_dump($this->app['database.service_container']);
        /** @var DatabaseServiceContainer $databaseServiceContainer */
        $databaseServiceContainer = $this->app['database.service_container'];

        $websites = $databaseServiceContainer->getWebsiteService()->getAll();
        $api = $this->app["controllers_factory"];

        $cacheService = null;
        if (class_exists($this->app['cache.class']))
        {
            $cacheService = new $this->app['cache.class']($this->app['cache.options']);
        }

        if (!class_exists($this->app['html.service']))
        {
            throw new ConfigurationException('Class for source retrieval does not exist: ' . $this->app['html.service']);
        }

        /** @var SourceRetrievalInterface $sourceRetrievalService */
        $sourceRetrievalService = new $this->app['html.service']($cacheService);

        foreach ($websites as $website)
        {
            $endpoints = $website->getEndpoints();
            foreach ($endpoints as $endpoint)
            {
                $api->get('/'.$website->getName().'/'.$endpoint->getName(), function () use ($databaseServiceContainer, $website, $endpoint, $sourceRetrievalService) {
                    $controller = new DefaultController($databaseServiceContainer, $sourceRetrievalService);
                    return $controller->processEndPoint($website, $endpoint);
                });

                $api->get('/'.$website->getName().'/'.$endpoint->getName().'/search/{key}/{value}', function ($key, $value) use ($databaseServiceContainer, $website, $endpoint, $sourceRetrievalService) {
                    $controller = new DefaultController($databaseServiceContainer, $sourceRetrievalService);
                    return $controller->search($website, $endpoint, $key, $value);
                });
            }
        }

        $api->post('/create',function(Request $request) use ($databaseServiceContainer, $sourceRetrievalService){
            $controller = new RequestController($databaseServiceContainer, $sourceRetrievalService);
            return $controller->create($request);
        });

        $api->post('/test',function(Request $request) use ($databaseServiceContainer, $sourceRetrievalService){
            $controller = new RequestController($databaseServiceContainer, $sourceRetrievalService);
            return $controller->test($request);
        });

        $api->put('/update/{id}',function(Request $request, $id) use ($databaseServiceContainer, $sourceRetrievalService){
            $controller = new RequestController($databaseServiceContainer, $sourceRetrievalService);
            return $controller->update($request, $id);
        });

        $api->delete('/delete/{websiteName}/{endpointName}',function($websiteName, $endpointName) use ($databaseServiceContainer, $sourceRetrievalService){
            $controller = new RequestController($databaseServiceContainer, $sourceRetrievalService);
            return $controller->delete($websiteName, $endpointName);
        });

        $api->match('{url}', function(){
            return new JsonResponse(['The requested end point does not exit', 404]);
        })->assert('url', '.+');

        $this->app->mount($this->app["api.endpoint"].'/'.$this->app["api.version"], $api);
    }
}

