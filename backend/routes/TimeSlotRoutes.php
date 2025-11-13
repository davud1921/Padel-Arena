<?php

Flight::route('GET /timeslots', function() {
    Flight::json(Flight::timeSlotService()->getAll());
});

Flight::route('GET /timeslots/@id', function($id) {
    Flight::json(Flight::timeSlotService()->getById($id));
});

Flight::route('POST /timeslots', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::timeSlotService()->createTimeSlot($data));
});

Flight::route('PUT /timeslots/@id', function($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::timeSlotService()->updateTimeSlot($id, $data));
});

Flight::route('DELETE /timeslots/@id', function($id) {
    Flight::json(Flight::timeSlotService()->delete($id));
});

?>
