<?php
require_once __DIR__ . '/../dao/TimeSlotDao.php';
require_once 'BaseService.php';

class TimeSlotService extends BaseService {

    public function __construct() {
        $dao = new TimeSlotsDao();
        parent::__construct($dao);
    }

    public function createTimeSlot($data) {
        if (!isset($data['court_id']) || !is_numeric($data['court_id'])) {
            throw new Exception("Invalid or missing court ID.");
        }

        if (!isset($data['date']) || trim($data['date']) === '') {
            throw new Exception("Date cannot be empty.");
        }

        if (!isset($data['start_time']) || trim($data['start_time']) === '') {
            throw new Exception("Start time cannot be empty.");
        }

        if (!isset($data['end_time']) || trim($data['end_time']) === '') {
            throw new Exception("End time cannot be empty.");
        }

        return $this->dao->createTimeSlot($data);
    }

    public function updateTimeSlot($id, $data) {
        return $this->dao->updateTimeSlot($id, $data);
    }

    public function setAvailability($id, $isAvailable) {
        if (!in_array($isAvailable, [0, 1])) {
            throw new Exception("Availability must be 0 or 1.");
        }

        $slot = $this->dao->getTimeSlotById($id);
        if (!$slot) {
            throw new Exception("Time slot not found.");
        }

        $slot['is_available'] = $isAvailable;

        return $this->dao->updateTimeSlot($id, $slot);
    }
}
?>
