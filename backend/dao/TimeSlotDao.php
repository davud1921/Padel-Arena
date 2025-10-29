<?php
require_once 'BaseDao.php';

class TimeSlotDao extends BaseDao {
    public function __construct() {
        parent::__construct("time_slots");
    }

    public function getAvailableSlots() {
        $stmt = $this->connection->prepare("SELECT * FROM time_slots WHERE available = 1");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function markAsUnavailable($id) {
        $stmt = $this->connection->prepare("UPDATE time_slots SET available = 0 WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>
