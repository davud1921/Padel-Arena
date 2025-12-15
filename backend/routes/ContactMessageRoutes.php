<?php

/**
 * @OA\Get(
 *     path="/contactmessages",
 *     tags={"contact_messages"},
 *     summary="Get all contact messages",
 *     @OA\Response(
 *         response=200,
 *         description="Returns all contact messages"
 *     )
 * )
 */
Flight::route('GET /contactmessages', function() {
    Flight::auth_middleware()->authorizeRoles([Roles::CUSTOMER, Roles::ADMIN]);
    Flight::json(Flight::contactMessageService()->getAll());
});

/**
 * @OA\Get(
 *     path="/contactmessages/{id}",
 *     tags={"contact_messages"},
 *     summary="Get contact message by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Contact message ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Returns a single contact message by ID"
 *     )
 * )
 */
Flight::route('GET /contactmessages/@id', function($id) {
    Flight::auth_middleware()->authorizeRoles([Roles::CUSTOMER, Roles::ADMIN]);
    Flight::json(Flight::contactMessageService()->getById($id));
});

/**
 * @OA\Get(
 *     path="/contactmessages/user/{user_id}",
 *     tags={"contact_messages"},
 *     summary="Get all contact messages by user ID",
 *     @OA\Parameter(
 *         name="user_id",
 *         in="path",
 *         required=true,
 *         description="User ID",
 *         @OA\Schema(type="integer", example=3)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Returns all messages belonging to a user"
 *     )
 * )
 */
Flight::route('GET /contactmessages/user/@user_id', function($user_id) {
    Flight::auth_middleware()->authorizeRoles([Roles::CUSTOMER, Roles::ADMIN]);
    Flight::json(Flight::contactMessageService()->getMessagesByUser($user_id));
});

/**
 * @OA\Post(
 *     path="/contactmessages",
 *     tags={"contact_messages"},
 *     summary="Create a new contact message",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id", "subject", "message"},
 *             @OA\Property(property="user_id", type="integer", example=2),
 *             @OA\Property(property="subject", type="string", example="Court Reservation Problem"),
 *             @OA\Property(property="message", type="string", example="I cannot book the court at 10 AM."),
 *             @OA\Property(property="date", type="string", example="2025-01-01 12:30:00")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="New contact message created"
 *     )
 * )
 */
Flight::route('POST /contactmessages', function() {
    Flight::auth_middleware()->authorizeRoles([Roles::CUSTOMER]);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::contactMessageService()->createContactMessage($data));
});

/**
 * @OA\Put(
 *     path="/contactmessages/{id}",
 *     tags={"contact_messages"},
 *     summary="Update an existing contact message",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Message ID",
 *         @OA\Schema(type="integer", example=4)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"subject", "message"},
 *             @OA\Property(property="subject", type="string", example="Updated subject text"),
 *             @OA\Property(property="message", type="string", example="Updated message content")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Contact message updated"
 *     )
 * )
 */
Flight::route('PUT /contactmessages/@id', function($id) {
    Flight::auth_middleware()->authorizeRoles([Roles::CUSTOMER, Roles::ADMIN]);
    $data = Flight::request()->data->getData();
    Flight::json(Flight::contactMessageService()->update($id, $data));
});

/**
 * @OA\Delete(
 *     path="/contactmessages/{id}",
 *     tags={"contact_messages"},
 *     summary="Delete a contact message by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the message to delete",
 *         @OA\Schema(type="integer", example=5)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Contact message deleted"
 *     )
 * )
 */
Flight::route('DELETE /contactmessages/@id', function($id) {
    Flight::auth_middleware()->authorizeRoles([Roles::CUSTOMER, Roles::ADMIN]);
    Flight::json(Flight::contactMessageService()->delete($id));
});

?>
