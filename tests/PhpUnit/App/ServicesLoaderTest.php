<?php

declare(strict_types=1);

namespace PhpUnit\App;

use App\Services\DatabaseServiceContainer;
use App\ServicesLoader;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use Silex\Application;

/**
 * @covers \App\ServicesLoader
 */
final class ServicesLoaderTest extends TestCase
{
    /**
     * @covers \App\ServicesLoader::bindServicesIntoContainer()
     */
    public function testBindServicesIntoContainer(): void
    {
        $app = new Application();
        $servicesLoader = new ServicesLoader($app);

        $app['entity.manager'] = $this->createMock(EntityManager::class);

        $this->assertFalse(isset($app['database.service_container']));
        $servicesLoader->bindServicesIntoContainer();
        $this->assertTrue(isset($app['database.service_container']));

        $this->assertInstanceOf(DatabaseServiceContainer::class, $app['database.service_container']);
    }
}