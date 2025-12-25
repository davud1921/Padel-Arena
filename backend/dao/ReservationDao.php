<?php
require_once 'BaseDao.php';

class ReservationDao extends BaseDao {
    public function __construct() {
        parent::__construct('reservations');
    }

    public function createReservation($reservation) {
        $data = [
            'user_id'     => $reservation['user_id'],
            'court_id'    => $reservation['court_id'],
            'reservation_date' => $reservation['reservation_date'],
            'slot_id'     => $reservation['slot_id'],
            'total_price' => $reservation['total_price'],
            'status'      => $reservation['status'] ?? 'Pending'
        ];
        return $this->insert($data);
    }

    public function getAllReservations() {
        return $this->getAll();
    }

    public function getReservationById($id) {
        return $this->getById($id);
    }

    public function updateReservation($id, $reservation) {
        $data = [
            'user_id'     => $reservation['user_id'],
            'court_id'    => $reservation['court_id'],
            'reservation_date' => $reservation['reservation_date'],
            'slot_id'     => $reservation['slot_id'],
            'total_price' => $reservation['total_price'],
            'status'      => $reservation['status']
        ];
        return $this->update($id, $data);
    }

    public function deleteReservation($id) {
        return $this->delete($id);
    }
}
?>