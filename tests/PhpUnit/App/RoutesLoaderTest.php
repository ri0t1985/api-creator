<?php

declare(strict_types=1);

namespace PhpUnit\App;

use App\Cache\Redis;
use App\Entities\Endpoint;
use App\Entities\Website;
use App\Exceptions\ConfigurationException;
use App\Helpers\HtmlParser;
use App\RoutesLoader;
use App\Services\DatabaseServiceContainer;
use App\Services\WebsiteService;
use PHPUnit\Framework\TestCase;
use Silex\Application;
use Silex\Controller;
use Silex\ControllerCollection;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;

/**
 * @covers \App\RoutesLoader
 */
final class RoutesLoaderTest extends TestCase
{
    /**
     * @covers \App\RoutesLoader::bindRoutesToControllers()
     */
    public function testBindRoutesToControllers(): void
    {
        $app = new Application();

        $websiteService = $this->createMock(WebsiteService::class);
        $websites = $this->getWebsites();
        $websiteService->method('getAll')->willReturn($websites);

        $databaseServiceContainer = $this->createMock(DatabaseServiceContainer::class);
        $databaseServiceContainer->expects($this->any())->method('getWebsiteService')->willReturn($websiteService);

        $controllerMock = $this->getMockBuilder(Controller::class)
            ->setMethods(['assert'])
            ->disableOriginalConstructor()
            ->getMock();

        $controllerMock->expects($this->once())->method('assert')->willReturn('true');

        $api = $this->getMockBuilder(ControllerCollection::class)
            ->disableOriginalConstructor()
            ->setMethods(['put', 'match', 'post', 'get', 'delete'])
            ->getMock();

        $api->expects($this->once())->method('delete');
        $api->expects($this->exactly(9))->method('get');
        $api->expects($this->exactly(2))->method('post');
        $api->expects($this->exactly(1))->method('put');
        $api->expects($this->exactly(1))->method('match')->willReturn($controllerMock);

        $app['database.service_container'] = $databaseServiceContainer;
        $app['cache.class']                = Redis::class;
        $app['cache.options']              = [];
        $app['html.service']               = HtmlParser::class;
        $app['controllers_factory']        = $api;
        $app['api.endpoint']               = 'Api';
        $app['api.version']                = 'V1';

        $routesLoader = new RoutesLoader($app);
        $routesLoader->bindRoutesToControllers();
    }

    public function testInvalidHtmlClass()
    {
        $app = new Application();

        $websiteService = $this->createMock(WebsiteService::class);

        $databaseServiceContainer = $this->createMock(DatabaseServiceContainer::class);
        $databaseServiceContainer->expects($this->any())->method('getWebsiteService')->willReturn($websiteService);

        $app['database.service_container'] = $databaseServiceContainer;
        $app['cache.class']                = Redis::class;
        $app['cache.options']              = [];
        $app['html.service']               = 'SomeClassThatDoesNotExist';

        $this->expectException(ConfigurationException::class);
        $this->expectExceptionMessage('Class for source retrieval does not exist: SomeClassThatDoesNotExist');

        $routesLoader = new RoutesLoader($app);
        $routesLoader->bindRoutesToControllers();
    }

    /**
     * @return Website[]
     */
    protected function getWebsites()
    {
        return [
            $this->createWebsite('websiteA'),
            $this->createWebsite('websiteB'),
        ];
    }

    /**
     * Creates a website mock object.
     * @param string $name
     * @return \PHPUnit_Framework_MockObject_MockObject|Website
     */
    protected function createWebsite($name)
    {
        $website = $this->createMock(Website::class);
        $website->expects($this->atLeastOnce())->method('getName')->willReturn($name);

        $endpointA = $this->createMock(Endpoint::class);
        $endpointA->expects($this->atLeastOnce())->method('getName')->willReturn($name.'_endpointA');
        $endpointB = $this->createMock(Endpoint::class);
        $endpointB->expects($this->atLeastOnce())->method('getName')->willReturn($name.'_endpointB');

        $website->expects($this->atLeastOnce())->method('getEndpoints')->willReturn([$endpointA, $endpointB]);

        return $website;
    }
}