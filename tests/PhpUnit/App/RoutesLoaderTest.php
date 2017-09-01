<?php

declare(strict_types=1);

namespace PhpUnit\App;

use App\Entities\Endpoint;
use App\Entities\Website;
use App\RoutesLoader;
use App\Services\DatabaseServiceContainer;
use App\Services\WebsiteService;
use PHPUnit\Framework\TestCase;
use Silex\Application;
use Silex\ControllerCollection;

/**
 * @covers RoutesLoader
 */
final class RoutesLoaderTest extends TestCase
{
    public function testBindRoutesToControllers(): void
    {

        /** @var Application|\PHPUnit_Framework_MockObject_MockObject $app */
        $app = $this->createMock(Application::class);

        $websiteService = $this->createMock(WebsiteService::class);
        $websites = $this->getWebsites();
        $websiteService->method('getAll')->willReturn($websites);


        $databaseServiceContainer = $this->createMock(DatabaseServiceContainer::class);


        $databaseServiceContainer->expects($this->once())->method('getWebsiteService')->willReturn($websiteService);


        $app->expects($this->any())->method('offsetGet')->with('database.service_container')->willReturn($databaseServiceContainer);
        $app->expects($this->any())->method('offsetGet')->with('controllers_factory')->willReturn($this->createMock(ControllerCollection::class));

        $routesLoader = new RoutesLoader($app);


        $routesLoader->bindRoutesToControllers();

        $this->markTestIncomplete();

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


    protected function createWebsite($name)
    {
        $website = $this->createMock(Website::class);
        $website->expects($this->atLeastOnce())->method('getName')->willReturn($name);

        $endpointA = $this->createMock(Endpoint::class);
        $endpointA->expects($this->once())->method('getName')->willReturn($name.'_endpointA');
        $endpointB = $this->createMock(Endpoint::class);
        $endpointB->expects($this->once())->method('getName')->willReturn($name.'_endpointB');



        $website->expects($this->once())->method('getEndpoints')->willReturn([$endpointA, $endpointB]);
    }
}