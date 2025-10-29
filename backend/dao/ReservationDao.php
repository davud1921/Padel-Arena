<?php
require_once 'BaseDao.php';

class ReservationDao extends BaseDao {
    public function __construct() {
        parent::__construct("reservations");
    }

    public function getByUserId($user_id) {
         return $this->getById($user_id);
    }

    public function updateStatus($id, $status) {
        $stmt = $this->connection->prepare("UPDATE reservations SET status = :status WHERE id = :id");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>
