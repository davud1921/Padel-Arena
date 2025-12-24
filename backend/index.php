<?php
require 'vendor/autoload.php';

require_once __DIR__ . '/services/AuthService.php';
require_once __DIR__ . '/services/ContactMessageService.php';
require_once __DIR__ . '/services/CourtService.php';
require_once __DIR__ . '/services/PaymentService.php';
require_once __DIR__ . '/services/ReservationService.php';
require_once __DIR__ . '/services/TimeSlotService.php';
require_once __DIR__ . '/services/UserService.php';
require_once __DIR__ . '/middleware/AuthMiddleware.php';
require_once __DIR__ . '/data/roles.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

Flight::register('auth_service', 'AuthService');
Flight::register('contactMessageService', 'ContactMessageService');
Flight::register('courtService', 'CourtService');
Flight::register('paymentService', 'PaymentService');
Flight::register('reservationService', 'ReservationService');
Flight::register('timeSlotService', 'TimeSlotService');
Flight::register('userService', 'UserService');
Flight::register('auth_middleware', 'AuthMiddleware');


Flight::before('start', function() {
    if(
        strpos(Flight::request()->url, '/auth/login') === 0 ||
        strpos(Flight::request()->url, '/auth/register') === 0 ||
        strpos(Flight::request()->url, '/public') === 0 
    ) {
        return TRUE;
    } else {
        try {
            $token = Flight::request()->getHeader("Authentication");
            if(Flight::auth_middleware()->verifyToken($token))
                return TRUE;
        } catch (\Exception $e) {
            Flight::halt(401, $e->getMessage());
        }
    }
});

require_once __DIR__ .'/routes/AuthRoutes.php';
require_once __DIR__ . '/routes/ContactMessageRoutes.php';
require_once __DIR__ . '/routes/CourtRoutes.php';
require_once __DIR__ . '/routes/PaymentRoutes.php';
require_once __DIR__ . '/routes/ReservationRoutes.php';
require_once __DIR__ . '/routes/TimeSlotRoutes.php';
require_once __DIR__ . '/routes/UserRoutes.php';

Flight::start();