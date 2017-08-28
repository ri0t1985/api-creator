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
//    public function update($id, $selector)
//    {
//
//        return $this->db->update('selectors', $selector, ['id' => $id]);
//    }
//
//    public function delete($id)
//    {
//        return $this->db->delete("selectors", array("id" => $id));
//    }
//
//    public function getAllByWebsiteIdAndEndpointId($websiteId, $endpointId)
//    {
//        return $this->db->fetchAll("SELECT s.* FROM selectors s LEFT JOIN endpoints e ON (e.id = s.endpoint_id) WHERE website_id=:website_id AND endpoint_id=:endpoint_id ", ['website_id' => $websiteId, 'endpoint_id' => $endpointId]);
//    }
//
//    public function getAllByWebsiteId($websiteId)
//    {
//        return $this->db->fetchAll("SELECT s.* FROM selectors s LEFT JOIN endpoints e ON (e.id = s.endpoint_id) WHERE website_id=:website_id",['website_id' => $websiteId]);
//    }

    protected function getRepository()
    {
        return $this->entityManager->getRepository(Entities\Selector::class);
    }
}
