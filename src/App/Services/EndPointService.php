<?php

namespace App\Services;

class EndPointService extends BaseService
{

    public function getOne($id)
    {
        return $this->db->fetchAssoc("SELECT * FROM endpoints WHERE id=?", [$id]);
    }

    public function getAll()
    {
        return $this->db->fetchAll("SELECT * FROM endpoints");
    }

    public function save($endpoints)
    {
        $this->db->insert("endpoints", $endpoints);
        return $this->db->lastInsertId();
    }

    public function update($id, $endpoint)
    {
        return $this->db->update('endpoints', $endpoint, ['id' => $id]);
    }

    public function delete($id)
    {
        return $this->db->delete("endpoints", array("id" => $id));
    }

    public function getAllByWebsiteId($websiteId)
    {
        return $this->db->fetchAll('SELECT e.* FROM endpoints e WHERE website_id=:website_id',['website_id' => $websiteId]);
    }

    public function getOneByName($name)
    {
        return $this->db->fetchAssoc("SELECT * FROM endpoints WHERE name=?", [$name]);
    }
}
