<?php

namespace App\Services;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

abstract class BaseService
{
    /** @var EntityManager */
    protected $entityManager;

    /**
     * BaseService constructor.
     * @param EntityManager $entityManager
     */
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /** @return EntityRepository */
    abstract protected function getRepository();
    //


    public function getOne($id)
    {
        return $this->getRepository()->find($id);
    }

    public function getAll()
    {
        return $this->getRepository()->findAll();
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }
}
