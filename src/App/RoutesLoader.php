<?php

namespace App;

use App\Controllers\DefaultController;
use Silex\Application;

class RoutesLoader
{
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->instantiateControllers();

    }

    private function instantiateControllers()
    {
        $this->app['default.controller'] = function() {
            return new DefaultController(
            );
        };
    }

    public function bindRoutesToControllers()
    {
        $api = $this->app["controllers_factory"];

        $api->get('/soccer/matches', "default.controller:getSoccerMatches");
        $api->get('/soccer/matches/{id}', "default.controller:getSoccerMatch");

        $this->app->mount($this->app["api.endpoint"].'/'.$this->app["api.version"], $api);
    }
}

