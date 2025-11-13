<?php

Flight::route('GET /courts', function() {
    Flight::json(Flight::courtService()->getAll());
});

Flight::route('GET /courts/@id', function($id) {
    Flight::json(Flight::courtService()->getById($id));
});

Flight::route('GET /courts/status/@status', function($status) {
    Flight::json(Flight::courtService()->getCourtsByStatus($status));
});

Flight::route('POST /courts', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::courtService()->createCourt($data));
});

Flight::route('PUT /courts/@id', function($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::courtService()->updateCourt($id, $data));
});

Flight::route('DELETE /courts/@id', function($id) {
    Flight::json(Flight::courtService()->delete($id));
});

?>
