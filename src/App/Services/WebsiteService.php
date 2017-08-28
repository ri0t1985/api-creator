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
//    public function save($website)
//    {
//        $this->db->insert("websites", $website);
//        return $this->db->lastInsertId();
//    }

//    public function update($id, $website)
//    {
//        return $this->db->update('websites', $website, ['id' => $id]);
//    }
//
//    public function delete($id)
//    {
//        return $this->db->delete("websites", array("id" => $id));
//    }
//
//    public function getOneByName($websiteName)
//    {
//        return $this->db->fetchAssoc("SELECT * FROM websites WHERE name=?", [$websiteName]);
//    }

    protected function getRepository()
    {
        return $this->entityManager->getRepository(Entities\Website::class);
    }
}
