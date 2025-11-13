<?php

/**
 * @OA\Get(
 *     path="/timeslots",
 *     tags={"timeslots"},
 *     summary="Get all time slots",
 *     @OA\Response(
 *         response=200,
 *         description="Returns a list of all time slots"
 *     )
 * )
 */
Flight::route('GET /timeslots', function() {
    Flight::json(Flight::timeSlotService()->getAll());
});

/**
 * @OA\Get(
 *     path="/timeslots/{id}",
 *     tags={"timeslots"},
 *     summary="Get a time slot by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Time slot ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Returns a single time slot by ID"
 *     )
 * )
 */
Flight::route('GET /timeslots/@id', function($id) {
    Flight::json(Flight::timeSlotService()->getById($id));
});

/**
 * @OA\Post(
 *     path="/timeslots",
 *     tags={"timeslots"},
 *     summary="Create a new time slot",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"court_id", "date", "start_time", "end_time", "is_available"},
 *             @OA\Property(property="court_id", type="integer", example=2, description="Court ID for this time slot"),
 *             @OA\Property(property="date", type="string", format="date", example="2025-11-15", description="Date of the time slot"),
 *             @OA\Property(property="start_time", type="string", format="time", example="09:00:00", description="Start time"),
 *             @OA\Property(property="end_time", type="string", format="time", example="10:00:00", description="End time"),
 *             @OA\Property(property="is_available", type="boolean", example=true, description="Availability status of the slot")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="New time slot created successfully"
 *     )
 * )
 */
Flight::route('POST /timeslots', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::timeSlotService()->createTimeSlot($data));
});

/**
 * @OA\Put(
 *     path="/timeslots/{id}",
 *     tags={"timeslots"},
 *     summary="Update an existing time slot",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Time slot ID to update",
 *         @OA\Schema(type="integer", example=3)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="court_id", type="integer", example=1, description="Updated court ID"),
 *             @OA\Property(property="date", type="string", format="date", example="2025-11-20", description="Updated date"),
 *             @OA\Property(property="start_time", type="string", format="time", example="14:00:00", description="Updated start time"),
 *             @OA\Property(property="end_time", type="string", format="time", example="15:00:00", description="Updated end time"),
 *             @OA\Property(property="is_available", type="boolean", example=false, description="Updated availability status")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Time slot updated successfully"
 *     )
 * )
 */
Flight::route('PUT /timeslots/@id', function($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::timeSlotService()->updateTimeSlot($id, $data));
});

/**
 * @OA\Delete(
 *     path="/timeslots/{id}",
 *     tags={"timeslots"},
 *     summary="Delete a time slot by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Time slot ID to delete",
 *         @OA\Schema(type="integer", example=4)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Time slot deleted successfully"
 *     )
 * )
 */
Flight::route('DELETE /timeslots/@id', function($id) {
    Flight::json(Flight::timeSlotService()->delete($id));
});

?>
