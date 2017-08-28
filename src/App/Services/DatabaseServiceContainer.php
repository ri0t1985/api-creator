<?php

namespace App\Services;

use Doctrine\ORM\EntityManager;

class DatabaseServiceContainer
{
    /** @var EndPointService  */
    protected $endpointService;
    /** @var SelectorService  */
    protected $selectorService;
    /** @var WebsiteService  */
    protected $websiteService;
    /** @var EntityManager */
    protected $entityManager;

    /**
     * DatabaseServiceContainer constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;

        $this->endpointService = new EndPointService($entityManager);
        $this->selectorService = new SelectorService($entityManager);
        $this->websiteService  = new WebsiteService($entityManager);
    }

    /**
     * @return WebsiteService
     */
    public function getWebsiteService()
    {
        return $this->websiteService;
    }

    /**
     * @return SelectorService
     */
    public function getSelectorService()
    {
        return $this->selectorService;
    }

    /**
     * @return EndPointService
     */
    public function getEndPointService()
    {
        return $this->endpointService;
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    public function getConnection()
    {
        return $this->entityManager->getConnection();
    }
}