<?php

namespace App\Services;

class WebsiteService extends BaseService
{

    public function getOne($id)
    {
        return $this->db->fetchAssoc("SELECT * FROM websites WHERE id=?", [$id]);
    }

    public function getAll()
    {
        return $this->db->fetchAll("SELECT * FROM websites");
    }

    public function save($website)
    {
        $this->db->insert("websites", $website);
        return $this->db->lastInsertId();
    }

    public function update($id, $website)
    {
        return $this->db->update('websites', $website, ['id' => $id]);
    }

    public function delete($id)
    {
        return $this->db->delete("websites", array("id" => $id));
    }

    public function getOneByName($websiteName)
    {
        return $this->db->fetchAssoc("SELECT * FROM websites WHERE name=?", [$websiteName]);
    }
}
