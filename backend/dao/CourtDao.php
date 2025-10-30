<?php
require_once 'BaseDao.php';

class CourtDao extends BaseDao {
    public function __construct() {
        parent::__construct('courts');
    }

    public function createCourt($court) {
        $data = [
            'name'           => $court['name'],
            'type'           => $court['type'],
            'location'       => $court['location'],
            'price_per_hour' => $court['price_per_hour'],
            'status'         => $court['status'] ?? 'Available'
        ];
        return $this->insert($data);
    }

    public function getAllCourts() {
        return $this->getAll();
    }

    public function getCourtById($id) {
        return $this->getById($id);
    }

    public function updateCourt($id, $court) {
        $data = [
            'name'           => $court['name'],
            'type'           => $court['type'],
            'location'       => $court['location'],
            'price_per_hour' => $court['price_per_hour'],
            'status'         => $court['status']
        ];
        return $this->update($id, $data);
    }

    public function deleteCourt($id) {
        return $this->delete($id);
    }
}
?>