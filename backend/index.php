<?php
require 'vendor/autoload.php';

require_once __DIR__ . '/services/ContactMessageService.php';
Flight::register('contactMessageService', 'ContactMessageService');
require_once __DIR__ . '/routes/ContactMessageRoutes.php';

require_once __DIR__ . '/services/CourtService.php';
Flight::register('courtService', 'CourtService');
require_once __DIR__ . '/routes/CourtRoutes.php';

require_once __DIR__ . '/services/PaymentService.php';
Flight::register('paymentService', 'PaymentService');
require_once __DIR__ . '/routes/PaymentRoutes.php';

require_once __DIR__ . '/services/ReservationService.php';
Flight::register('reservationService', 'ReservationService');
require_once __DIR__ . '/routes/ReservationRoutes.php';

require_once __DIR__ . '/services/TimeSlotService.php';
Flight::register('timeSlotService', 'TimeSlotService');
require_once __DIR__ . '/routes/TimeSlotRoutes.php';

require_once __DIR__ . '/services/UserService.php';
Flight::register('userService', 'UserService');
require_once __DIR__ . '/routes/UserRoutes.php';

Flight::start();