<?php

/**
 * @OA\Get(
 *     path="/users",
 *     tags={"users"},
 *     summary="Get all users",
 *     @OA\Response(
 *         response=200,
 *         description="Returns a list of all users"
 *     )
 * )
 */
Flight::route('GET /users', function() {
    Flight::auth_middleware()->authorizeRoles([Roles::CUSTOMER]);
    Flight::json(Flight::userService()->getAll());
});

/**
 * @OA\Get(
 *     path="/users/{id}",
 *     tags={"users"},
 *     summary="Get user by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="User ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Returns a single user by ID"
 *     )
 * )
 */
Flight::route('GET /users/@id', function($id) {
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);
    Flight::json(Flight::userService()->getById($id));
});

/**
 * @OA\Post(
 *     path="/users",
 *     tags={"users"},
 *     summary="Create a new user",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "email", "password", "role"},
 *             @OA\Property(property="name", type="string", example="John Doe", description="User's full name"),
 *             @OA\Property(property="email", type="string", format="email", example="john@example.com", description="User's email address"),
 *             @OA\Property(property="password", type="string", example="securePassword123", description="User's password (hashed in backend)"),
 *             @OA\Property(property="role", type="string", enum={"Admin","Customer"}, example="Customer", description="User role")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="New user created successfully"
 *     )
 * )
 */
Flight::route('POST /users', function() {
    Flight::auth_middleware()->authorizeRoles([Roles::CUSTOMER, Roles::ADMIN]);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::userService()->createUser($data));
});

/**
 * @OA\Put(
 *     path="/users/{id}",
 *     tags={"users"},
 *     summary="Update an existing user",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="User ID to update",
 *         @OA\Schema(type="integer", example=2)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string", example="Updated User", description="Updated full name"),
 *             @OA\Property(property="email", type="string", format="email", example="updated.email@example.com", description="Updated email address"),
 *             @OA\Property(property="password", type="string", example="newPassword456", description="Updated password (hashed in backend)"),
 *             @OA\Property(property="role", type="string", enum={"Admin","Customer"}, example="Admin", description="Updated user role")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User updated successfully"
 *     )
 * )
 */
Flight::route('PUT /users/@id', function($id) {
    Flight::auth_middleware()->authorizeRoles([Roles::CUSTOMER, Roles::ADMIN]);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::userService()->updateUser($id, $data));
});

/**
 * @OA\Delete(
 *     path="/users/{id}",
 *     tags={"users"},
 *     summary="Delete a user by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="User ID to delete",
 *         @OA\Schema(type="integer", example=3)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User deleted successfully"
 *     )
 * )
 */
Flight::route('DELETE /users/@id', function($id) {
    Flight::auth_middleware()->authorizeRoles([Roles::CUSTOMER, Roles::ADMIN]);
    Flight::json(Flight::userService()->delete($id));
});

?>