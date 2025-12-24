<?php

/**
 * @OA\Get(
 *     path="/reservations",
 *     tags={"reservations"},
 *     summary="Get all reservations",
 *     @OA\Response(
 *         response=200,
 *         description="Returns a list of all reservations"
 *     )
 * )
 */
Flight::route('GET /reservations', function() {
    Flight::auth_middleware()->authorizeRoles([Roles::CUSTOMER, Roles::ADMIN]);
    Flight::json(Flight::reservationService()->getAll());
});

/**
 * @OA\Get(
 *     path="/reservations/{id}",
 *     tags={"reservations"},
 *     summary="Get reservation by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Reservation ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Returns a single reservation by ID"
 *     )
 * )
 */
Flight::route('GET /reservations/@id', function($id) {
    Flight::auth_middleware()->authorizeRoles([Roles::CUSTOMER, Roles::ADMIN]);
    Flight::json(Flight::reservationService()->getById($id));
});

/**
 * @OA\Post(
 *     path="/reservations",
 *     tags={"reservations"},
 *     summary="Create a new reservation",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id", "court_id", "slot_id", "total_price", "status"},
 *             @OA\Property(property="user_id", type="integer", example=1, description="User who made the reservation"),
 *             @OA\Property(property="court_id", type="integer", example=2, description="Court being reserved"),
 *             @OA\Property(property="slot_id", type="integer", example=4, description="Selected time slot for the reservation"),
 *             @OA\Property(property="total_price", type="number", format="float", example=25.00, description="Total price for the reservation"),
 *             @OA\Property(property="status", type="string", enum={"Pending","Confirmed","Cancelled"}, example="Pending", description="Current status of the reservation")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="New reservation created successfully"
 *     )
 * )
 */
Flight::route('POST /reservations', function() {
    Flight::auth_middleware()->authorizeRoles([Roles::CUSTOMER]);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::reservationService()->createReservation($data));
});

/**
 * @OA\Put(
 *     path="/reservations/{id}",
 *     tags={"reservations"},
 *     summary="Update an existing reservation",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Reservation ID to update",
 *         @OA\Schema(type="integer", example=2)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="user_id", type="integer", example=3, description="Updated user ID"),
 *             @OA\Property(property="court_id", type="integer", example=1, description="Updated court ID"),
 *             @OA\Property(property="slot_id", type="integer", example=2, description="Updated slot ID"),
 *             @OA\Property(property="total_price", type="number", format="float", example=30.00, description="Updated total price"),
 *             @OA\Property(property="status", type="string", enum={"Pending","Confirmed","Cancelled"}, example="Confirmed", description="Updated reservation status")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Reservation updated successfully"
 *     )
 * )
 */
Flight::route('PUT /reservations/@id', function($id) {
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::reservationService()->updateReservation($id, $data));
});

/**
 * @OA\Delete(
 *     path="/reservations/{id}",
 *     tags={"reservations"},
 *     summary="Delete a reservation by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Reservation ID to delete",
 *         @OA\Schema(type="integer", example=3)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Reservation deleted successfully"
 *     )
 * )
 */
Flight::route('DELETE /reservations/@id', function($id) {
    Flight::auth_middleware()->authorizeRoles([Roles::CUSTOMER, Roles::ADMIN]);
    Flight::json(Flight::reservationService()->delete($id));
});

?>