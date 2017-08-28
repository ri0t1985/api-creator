<?php

namespace App\Services;

use App\Entities;

/**
 * Class SelectorService
 * @package App\Services
 *
 * @method Entities\Selector[] getAll()
 * @method Entities\Selector getOne($id)
 */
class SelectorService extends BaseService
{
    protected function getRepository()
    {
        return $this->entityManager->getRepository(Entities\Selector::class);
    }
}
