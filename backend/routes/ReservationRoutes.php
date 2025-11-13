<?php

Flight::route('GET /reservations', function() {
    Flight::json(Flight::reservationService()->getAll());
});

Flight::route('GET /reservations/@id', function($id) {
    Flight::json(Flight::reservationService()->getById($id));
});

Flight::route('POST /reservations', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::reservationService()->createReservation($data));
});

Flight::route('PUT /reservations/@id', function($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::reservationService()->updateReservation($id, $data));
});

Flight::route('DELETE /reservations/@id', function($id) {
    Flight::json(Flight::reservationService()->delete($id));
});

?>
