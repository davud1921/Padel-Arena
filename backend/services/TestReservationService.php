<?php
require_once 'ReservationService.php';

$reservation_service = new ReservationService();

try {
    $new_reservation = [
        'user_id'     => 1,
        'court_id'    => 1,
        'slot_id'     => 1,
        'total_price' => 20,
        'status'      => 'Pending'
    ];

    $result = $reservation_service->createReservation($new_reservation);
    echo "Reservation created successfully:\n";
    print_r($result);

    $reservations = $reservation_service->getAll();
    echo "\nAll reservations:\n";
    print_r($reservations);

    $reservation = $reservation_service->getById(1);
    echo "\nReservation with ID = 1:\n";
    print_r($reservation);

    $change = $reservation_service->changeStatus(1, 'Approved');
    echo "\nStatus updated (Reservation ID 1 → Approved):\n";
    print_r($change);

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
