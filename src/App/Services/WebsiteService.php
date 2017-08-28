<?php

namespace App\Services;

use App\Entities;

/**
 * Class WebsiteService
 * @package App\Services
 *
 * @method Entities\Website[] getAll()
 * @method Entities\Website getOne($id)
 */
class WebsiteService extends BaseService
{

    /**
     * @param string $websiteName
     * @return Entities\Website|object
     */
    public function getOneByName($websiteName)
    {
        return $this->getRepository()->findOneBy(['name' => $websiteName]);
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getRepository()
    {
        return $this->entityManager->getRepository(Entities\Website::class);
    }
}
