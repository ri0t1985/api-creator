<?php

namespace App\Services;

class BaseService
{
    /** @var \Doctrine\DBAL\Connection  */
    protected $db;

    /**
     * BaseService constructor.
     * @param \Doctrine\DBAL\Connection $db
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

}
