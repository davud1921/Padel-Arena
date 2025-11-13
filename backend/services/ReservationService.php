<?php
require_once __DIR__ . '/../dao/ReservationDao.php';
require_once 'BaseService.php';

class ReservationService extends BaseService {

    public function __construct() {
        $dao = new ReservationDao();
        parent::__construct($dao);
    }

    public function createReservation($data) {
        if (!isset($data['user_id']) || !is_numeric($data['user_id'])) {
            throw new Exception("Invalid or missing user ID.");
        }

        if (!isset($data['court_id']) || !is_numeric($data['court_id'])) {
            throw new Exception("Invalid or missing court ID.");
        }

        if (!isset($data['slot_id']) || !is_numeric($data['slot_id'])) {
            throw new Exception("Invalid or missing time slot ID.");
        }

        if (!isset($data['total_price']) || $data['total_price'] <= 0) {
            throw new Exception("Total price must be a positive value.");
        }

        return $this->dao->createReservation($data);
    }

    public function updateReservation($id, $data) {
        if (!isset($data['total_price']) || $data['total_price'] <= 0) {
            throw new Exception("Total price must be a positive value.");
        }

        return $this->dao->updateReservation($id, $data);
    }

    public function changeStatus($id, $status) {
        $allowed = ['Pending', 'Approved', 'Rejected'];

        if (!in_array($status, $allowed)) {
            throw new Exception("Invalid reservation status.");
        }

        return $this->dao->updateReservation($id, [
            'user_id'     => null, 
            'court_id'    => null,
            'slot_id'     => null,
            'total_price' => null,
            'status'      => $status
        ]);
    }
}
?>
