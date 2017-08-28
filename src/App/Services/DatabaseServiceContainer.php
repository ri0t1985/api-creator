<?php

namespace App\Services;

class DatabaseServiceContainer
{
    /** @var EndPointService  */
    protected $endpointService;
    /** @var SelectorService  */
    protected $selectorService;
    /** @var WebsiteService  */
    protected $websiteService;

    protected $connection;

    public function __construct(\Doctrine\DBAL\Connection $db)
    {
        $this->connection = $db;
        $this->endpointService = new EndPointService($db);
        $this->selectorService = new SelectorService($db);
        $this->websiteService  = new WebsiteService($db);
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
        return $this->connection;
    }
}