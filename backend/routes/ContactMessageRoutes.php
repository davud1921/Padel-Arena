<?php

Flight::route('GET /contactmessages', function() {
    Flight::json(Flight::contactMessageService()->getAll());
});

Flight::route('GET /contactmessages/@id', function($id) {
    Flight::json(Flight::contactMessageService()->getById($id));
});

Flight::route('GET /contactmessages/user/@user_id', function($user_id) {
    Flight::json(Flight::contactMessageService()->getMessagesByUser($user_id));
});

Flight::route('POST /contactmessages', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::contactMessageService()->createContactMessage($data));
});

Flight::route('PUT /contactmessages/@id', function($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::contactMessageService()->update($id, $data));
});

Flight::route('DELETE /contactmessages/@id', function($id) {
    Flight::json(Flight::contactMessageService()->delete($id));
});

?>
