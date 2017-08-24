<?php

namespace App\Services;

class SelectorService extends BaseService
{

    public function getOne($id)
    {
        return $this->db->fetchAssoc("SELECT * FROM selector WHERE id=?", [$id]);
    }

    public function getAll()
    {
        return $this->db->fetchAll("SELECT * FROM selector");
    }

    function save($note)
    {
        $this->db->insert("selector", $note);
        return $this->db->lastInsertId();
    }

    function update($id, $selector)
    {
        return $this->db->update('selector', $selector, ['id' => $id]);
    }

    function delete($id)
    {
        return $this->db->delete("selector", array("id" => $id));
    }

    public function getAllByWebsiteIdAndEndpointId($websiteId, $endpointId)
    {
        return $this->db->fetchAll("SELECT s.* FROM selectors s LEFT JOIN endpoints e ON (e.id = s.endpoint_id) WHERE website_id=:website_id AND endpoint_id=:endpoint_id ", ['website_id' => $websiteId, 'endpoint_id' => $endpointId]);
    }

}
