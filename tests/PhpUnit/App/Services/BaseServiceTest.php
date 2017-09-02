<?php

declare(strict_types=1);

namespace PhpUnit\App\Services;

use App\Services\BaseService;
use App\Services\DatabaseServiceContainer;
use App\Services\EndPointService;
use App\Services\SelectorService;
use App\Services\WebsiteService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Services\BaseService
 */
final class BaseServiceTest extends TestCase
{
    public function testConstructor()
    {
        /** @var EntityManager|PHPUnit_Framework_MockObject_MockObject */
        $entityManager = $this->createMock(EntityManager::class);
        $entityManager->expects($this->any())->method('getConnection')->willReturn('get_connection');

        $baseServiceMock = new BaseServiceMock($entityManager);
        $this->assertEquals($entityManager, $baseServiceMock->getEntityManager());
    }
}

class BaseServiceMock extends BaseService
{

    /** @return EntityRepository */
    protected function getRepository()
    {
        return false;
    }
}
