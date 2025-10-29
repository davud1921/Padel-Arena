<?php
require_once 'BaseDao.php';

class PaymentDao extends BaseDao {
    public function __construct() {
        parent::__construct("payments");
    }

    public function getByReservationId($reservation_id) {
         return $this->getById($reservation_id);
    }
}
?>
