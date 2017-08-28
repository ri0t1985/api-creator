<?php

namespace App;

use Silex\Application;

class ServicesLoader
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function bindServicesIntoContainer()
    {
        $this->app['database.service_container'] = function() {
            return new Services\DatabaseServiceContainer($this->app["entity.manager"]);
        };
    }
}

