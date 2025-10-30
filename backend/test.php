<?php
require_once 'dao/UserDao.php';
require_once 'dao/CourtDao.php';
require_once 'dao/TimeSlotDao.php';      
require_once 'dao/ReservationDao.php';
require_once 'dao/PaymentDao.php';
require_once 'dao/ContactMessageDao.php';

$userDao         = new UserDao();
$courtDao        = new CourtDao();
$timeSlotsDao    = new TimeSlotsDao();
$reservationDao  = new ReservationDao();
$paymentDao      = new PaymentDao();
$contactMsgDao   = new ContactMessageDao();

$pdo = Database::connect();

$userDao->insert([
    'name'     => 'Test Customer',
    'email'    => 'test.customer+' . time() . '@example.com',
    'password' => password_hash('StrongPass!123', PASSWORD_DEFAULT),
    'role'     => 'Customer' 
]);
$userId = $pdo->lastInsertId();

$courtDao->insert([
    'name'           => 'Court X',
    'type'           => 'Padel',
    'location'       => 'Center',
    'price_per_hour' => 25.00,
    'status'         => 'Available'
]);
$courtId = $pdo->lastInsertId();

$timeSlotsDao->insert([
    'court_id'     => $courtId,
    'date'         => date('Y-m-d', strtotime('+1 day')),
    'start_time'   => '09:00:00',
    'end_time'     => '10:00:00',
    'is_available' => 1
]);
$slotId = $pdo->lastInsertId();

$reservationDao->insert([
    'user_id'     => $userId,
    'court_id'    => $courtId,
    'slot_id'     => $slotId,
    'total_price' => 25.00,
    'status'      => 'Confirmed' 
]);
$reservationId = $pdo->lastInsertId();

$paymentDao->insert([
    'reservation_id' => $reservationId,
    'amount'         => 25.00,
    'method'         => 'Card', 
    'date'           => date('Y-m-d H:i:s')
]);

$contactMsgDao->insert([
    'user_id' => $userId,
    'subject' => 'Question about booking',
    'message' => 'Can I reschedule my timeslot to 10:00?',
    'date'    => date('Y-m-d H:i:s')
]);

echo "\n=== Users ===\n";
print_r($userDao->getAll());

echo "\n=== Courts ===\n";
print_r($courtDao->getAll());

echo "\n=== TimeSlots ===\n";
print_r($timeSlotsDao->getAll());

echo "\n=== Reservations ===\n";
print_r($reservationDao->getAll());

echo "\n=== Payments ===\n";
print_r($paymentDao->getAll());

echo "\n=== Contact Messages ===\n";
print_r($contactMsgDao->getAll());