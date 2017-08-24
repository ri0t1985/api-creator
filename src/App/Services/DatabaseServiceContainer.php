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

    public function __construct(\Doctrine\DBAL\Connection $db)
    {
        $this->endpointService = new EndPointService($db);
        $this->selectorService = new SelectorService($db);
        $this->websiteService  = new WebsiteService($db);
    }

    public function getWebsiteService()
    {
        return $this->websiteService;
    }

    public function getSelectorService()
    {
        return $this->selectorService;
    }

    public function getEndPointService()
    {
        return $this->endpointService;
    }
}