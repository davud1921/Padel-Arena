<?php

/**
 * @OA\Get(
 *     path="/courts",
 *     tags={"courts"},
 *     summary="Get all courts",
 *     @OA\Response(
 *         response=200,
 *         description="Returns a list of all courts"
 *     )
 * )
 */
Flight::route('GET /courts', function() {
    Flight::auth_middleware()->authorizeRoles([Roles::CUSTOMER, Roles::ADMIN]);
    Flight::json(Flight::courtService()->getAll());
});

/**
 * @OA\Get(
 *     path="/courts/{id}",
 *     tags={"courts"},
 *     summary="Get court by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Court ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Returns a single court by ID"
 *     )
 * )
 */
Flight::route('GET /courts/@id', function($id) {
    Flight::auth_middleware()->authorizeRoles([Roles::CUSTOMER, Roles::ADMIN]);
    Flight::json(Flight::courtService()->getById($id));
});

/**
 * @OA\Get(
 *     path="/courts/status/{status}",
 *     tags={"courts"},
 *     summary="Get all courts by status",
 *     @OA\Parameter(
 *         name="status",
 *         in="path",
 *         required=true,
 *         description="Court availability status (Available or Unavailable)",
 *         @OA\Schema(type="string", enum={"Available","Unavailable"}, example="Available")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Returns all courts matching the given status"
 *     )
 * )
 */
Flight::route('GET /courts/status/@status', function($status) {
    Flight::auth_middleware()->authorizeRoles([Roles::CUSTOMER, Roles::ADMIN]);
    Flight::json(Flight::courtService()->getCourtsByStatus($status));
});

/**
 * @OA\Post(
 *     path="/courts",
 *     tags={"courts"},
 *     summary="Create a new court",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "type", "price_per_hour", "status"},
 *             @OA\Property(property="name", type="string", example="Court D", description="Name of the court"),
 *             @OA\Property(property="type", type="string", example="Padel", description="Type of the court"),
 *             @OA\Property(property="location", type="string", example="East Side", description="Location of the court"),
 *             @OA\Property(property="price_per_hour", type="number", format="float", example=35.00, description="Hourly price for the court"),
 *             @OA\Property(property="status", type="string", enum={"Available","Unavailable"}, example="Available", description="Current court status")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="New court created successfully"
 *     )
 * )
 */
Flight::route('POST /courts', function() {
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::courtService()->createCourt($data));
});

/**
 * @OA\Put(
 *     path="/courts/{id}",
 *     tags={"courts"},
 *     summary="Update an existing court",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Court ID to update",
 *         @OA\Schema(type="integer", example=2)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string", example="Updated Court A", description="Updated court name"),
 *             @OA\Property(property="type", type="string", example="Padel", description="Court type"),
 *             @OA\Property(property="location", type="string", example="Downtown Complex", description="Updated court location"),
 *             @OA\Property(property="price_per_hour", type="number", format="float", example=40.00, description="Updated hourly price"),
 *             @OA\Property(property="status", type="string", enum={"Available","Unavailable"}, example="Unavailable", description="Updated availability status")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Court updated successfully"
 *     )
 * )
 */
Flight::route('PUT /courts/@id', function($id) {
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::courtService()->updateCourt($id, $data));
});

/**
 * @OA\Delete(
 *     path="/courts/{id}",
 *     tags={"courts"},
 *     summary="Delete a court by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Court ID to delete",
 *         @OA\Schema(type="integer", example=3)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Court deleted successfully"
 *     )
 * )
 */
Flight::route('DELETE /courts/@id', function($id) {
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);
    Flight::json(Flight::courtService()->delete($id));
});

?>