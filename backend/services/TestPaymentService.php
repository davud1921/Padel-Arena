<?php
require_once 'ReservationService.php';
require_once 'PaymentService.php';

$reservation_service = new ReservationService();
$payment_service     = new PaymentService();

try {
    $new_reservation = [
        'user_id'     => 1,
        'court_id'    => 1,
        'slot_id'     => 1,
        'total_price' => 30,
        'status'      => 'Pending'
    ];

    $reservation_result = $reservation_service->createReservation($new_reservation);
    echo "Reservation created:\n";
    print_r($reservation_result);

    $reservation_id = $reservation_service->getAll();
    $reservation_id = end($reservation_id)['id']; 

    echo "\nUsing reservation_id = $reservation_id\n";

    $new_payment = [
        'reservation_id' => $reservation_id,
        'amount'         => 30.50,
        'method'         => 'Card'
    ];

    $payment_result = $payment_service->createPayment($new_payment);
    echo "\nPayment created successfully:\n";
    print_r($payment_result);

    $payments = $payment_service->getAll();
    echo "\nAll payments:\n";
    print_r($payments);

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
