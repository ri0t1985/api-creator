<?php

namespace App\Services;

class WebsiteService extends BaseService
{

    public function getOne($id)
    {
        return $this->db->fetchAssoc("SELECT * FROM websites WHERE id=?", [(int) $id]);
    }

    public function getAll()
    {
        return $this->db->fetchAll("SELECT * FROM websites");
    }

    function save($website)
    {
        $this->db->insert("websites", $website);
        return $this->db->lastInsertId();
    }

    function update($id, $website)
    {
        return $this->db->update('websites', $website, ['id' => $id]);
    }

    function delete($id)
    {
        return $this->db->delete("notes", array("id" => $id));
    }

}
