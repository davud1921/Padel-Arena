<?php
require_once __DIR__ . '/TimeSlotService.php';

$slot_service = new TimeSlotService();

try {
    $new_slot = [
        'court_id'     => 1,                    
        'date'         => '2025-01-01',
        'start_time'   => '10:00:00',
        'end_time'     => '11:00:00',
        'is_available' => 1
    ];

    $result = $slot_service->createTimeSlot($new_slot);
    echo "Time slot created successfully:\n";
    print_r($result);

    $slots = $slot_service->getAll();
    echo "\nAll time slots:\n";
    print_r($slots);

    $slot_by_id = $slot_service->getById(1); 
    echo "\nTime slot with ID = 1:\n";
    print_r($slot_by_id);

    $slot_service->setAvailability(1, 0); 
    echo "\nAvailability updated for slot ID 1 (set to 0)\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
