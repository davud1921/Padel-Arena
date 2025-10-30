<?php
require_once 'BaseDao.php';

class TimeSlotsDao extends BaseDao {
    public function __construct() {
        parent::__construct('timeslots');
    }

    public function createTimeSlot($slot) {
        $data = [
            'court_id'     => $slot['court_id'],
            'date'         => $slot['date'],
            'start_time'   => $slot['start_time'],
            'end_time'     => $slot['end_time'],
            'is_available' => $slot['is_available'] ?? 1
        ];
        return $this->insert($data);
    }

    public function getAllTimeSlots() {
        return $this->getAll();
    }

    public function getTimeSlotById($id) {
        return $this->getById($id);
    }

    public function updateTimeSlot($id, $slot) {
        $data = [
            'court_id'     => $slot['court_id'],
            'date'         => $slot['date'],
            'start_time'   => $slot['start_time'],
            'end_time'     => $slot['end_time'],
            'is_available' => $slot['is_available']
        ];
        return $this->update($id, $data);
    }

    public function deleteTimeSlot($id) {
        return $this->delete($id);
    }
}
?>