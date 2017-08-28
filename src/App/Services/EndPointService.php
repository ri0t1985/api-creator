<?php

namespace App\Services;

use Doctrine\ORM\EntityManager;
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
//    public function save($endpoints)
//    {
//        $this->db->insert("endpoints", $endpoints);
//        return $this->db->lastInsertId();
//    }
//
//    public function update($id, $endpoint)
//    {
//        return $this->db->update('endpoints', $endpoint, ['id' => $id]);
//    }
//
//    public function delete($id)
//    {
//        return $this->db->delete("endpoints", array("id" => $id));
//    }
//
//    public function getAllByWebsiteId($websiteId)
//    {
//        return $this->db->fetchAll('SELECT e.* FROM endpoints e WHERE website_id=:website_id',['website_id' => $websiteId]);
//    }
//
//    public function getOneByName($name)
//    {
//        return $this->db->fetchAssoc("SELECT * FROM endpoints WHERE name=?", [$name]);
//    }

    protected function getRepository()
    {
        return $this->entityManager->getRepository(Entities\Endpoint::class);
    }
}
