<?php

namespace App\Services;

use App\Entities;

/**
 * Class EndPointService
 * @package App\Services
 *
 * @method getAll() Entities\Endpoint[]
 * @method getOne($id) Entities\Endpoint
 */
class EndPointService extends BaseService
{
    /**
     * @param string $name
     * @return Entities\Endpoint
     */
    public function getOneByName($name)
    {
        return $this->getRepository()->findOneBy(['name' => $name]);
    }

    /**
     * Returns the repository for the Endpoint entity.
     *
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getRepository()
    {
        return $this->entityManager->getRepository(Entities\Endpoint::class);
    }
}
