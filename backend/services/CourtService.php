<?php
require_once __DIR__ . '/../dao/CourtDao.php';
require_once 'BaseService.php';

class CourtService extends BaseService {

    public function __construct() {
        $dao = new CourtDao();
        parent::__construct($dao);
    }

    public function createCourt($data) {
        if (!isset($data['name']) || trim($data['name']) === '') {
            throw new Exception("Court name cannot be empty.");
        }

        if (!isset($data['type']) || trim($data['type']) === '') {
            throw new Exception("Court type cannot be empty.");
        }

        if (!isset($data['price_per_hour']) || $data['price_per_hour'] <= 0) {
            throw new Exception("Price per hour must be a positive value.");
        }

        return $this->dao->createCourt($data);
    }

    public function getCourtsByStatus($status) {
        return $this->dao->getCourtsByStatus($status);
    }

    public function updateCourt($id, $data) {
        return $this->dao->updateCourt($id, $data);
    }
}
?>
