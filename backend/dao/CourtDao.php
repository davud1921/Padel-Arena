<?php
require_once 'BaseDao.php';

class CourtDao extends BaseDao {
    public function __construct() {
        parent::__construct("courts");
    }

    public function getByType($type) {
        $stmt = $this->connection->prepare("SELECT * FROM courts WHERE type = :type");
        $stmt->bindParam(':type', $type);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>
