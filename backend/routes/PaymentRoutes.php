<?php

/**
 * @OA\Get(
 *     path="/payments",
 *     tags={"payments"},
 *     summary="Get all payments",
 *     @OA\Response(
 *         response=200,
 *         description="Returns a list of all payments"
 *     )
 * )
 */
Flight::route('GET /payments', function() {
    Flight::auth_middleware()->authorizeRoles([Roles::CUSTOMER, Roles::ADMIN]);
    Flight::json(Flight::paymentService()->getAll());
});

/**
 * @OA\Get(
 *     path="/payments/{id}",
 *     tags={"payments"},
 *     summary="Get payment by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Payment ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Returns a single payment by ID"
 *     )
 * )
 */
Flight::route('GET /payments/@id', function($id) {
    Flight::auth_middleware()->authorizeRoles([Roles::CUSTOMER, Roles::ADMIN]);
    Flight::json(Flight::paymentService()->getById($id));
});

/**
 * @OA\Post(
 *     path="/payments",
 *     tags={"payments"},
 *     summary="Create a new payment",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"reservation_id", "amount", "method"},
 *             @OA\Property(property="reservation_id", type="integer", example=1, description="Related reservation ID"),
 *             @OA\Property(property="amount", type="number", format="float", example=25.00, description="Amount paid"),
 *             @OA\Property(property="method", type="string", enum={"Cash","Card","Online"}, example="Card", description="Payment method"),
 *             @OA\Property(property="date", type="string", example="2025-11-13 12:00:00", description="Payment date (optional)")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="New payment created successfully"
 *     )
 * )
 */
Flight::route('POST /payments', function() {
    Flight::auth_middleware()->authorizeRoles([Roles::CUSTOMER]);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::paymentService()->createPayment($data));
});

/**
 * @OA\Put(
 *     path="/payments/{id}",
 *     tags={"payments"},
 *     summary="Update an existing payment",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Payment ID",
 *         @OA\Schema(type="integer", example=2)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="amount", type="number", format="float", example=30.00, description="Updated payment amount"),
 *             @OA\Property(property="method", type="string", enum={"Cash","Card","Online"}, example="Online", description="Updated payment method"),
 *             @OA\Property(property="date", type="string", example="2025-11-15 10:00:00", description="Updated payment date (optional)")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Payment updated successfully"
 *     )
 * )
 */
Flight::route('PUT /payments/@id', function($id) {
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::paymentService()->updatePayment($id, $data));
});

/**
 * @OA\Delete(
 *     path="/payments/{id}",
 *     tags={"payments"},
 *     summary="Delete a payment by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Payment ID to delete",
 *         @OA\Schema(type="integer", example=3)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Payment deleted successfully"
 *     )
 * )
 */
Flight::route('DELETE /payments/@id', function($id) {
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);
    Flight::json(Flight::paymentService()->delete($id));
});

?>