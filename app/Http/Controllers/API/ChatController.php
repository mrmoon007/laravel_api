<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/messages/{user}",
     *     summary="Get all messages between the authenticated user and another user",
     *     description="Returns a list of messages exchanged between the authenticated user and the specified user.",
     *     operationId="getMessages",
     *     tags={"Messages"},
     *     security={{"bearer":{}}},
     *
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         required=true,
     *         description="ID of the user to fetch messages with",
     *         @OA\Schema(type="integer", example="1")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="message create successfully!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="sender_id", type="integer", example=1),
     *                     @OA\Property(property="receiver_id", type="integer", example=2),
     *                     @OA\Property(property="message", type="string", example="Hello!"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-17T10:20:30Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-17T10:20:30Z")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthenticated"),
     *             @OA\Property(property="data", type="array", @OA\Items())
     *         )
     *     )
     * )
     */
    public function getMessages(User $user)
    {

        try {

            $messages = Message::where(function ($q) use ($user) {
                $q->where('sender_id', auth()->id())
                    ->where('receiver_id', $user->id);
            })->orWhere(function ($q) use ($user) {
                $q->where('sender_id', $user->id)
                    ->where('receiver_id', auth()->id());
            })->orderBy('created_at')->get();

            return response()->json([
                'status' => true,
                'message' => 'message create successfully!',
                'data' => $messages
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
                'data' => []
            ], 200);
        }
    }


    /**
     * @OA\Post(
     *     path="/api/messages/send",
     *     summary="Send a message to another user",
     *     description="Sends a message from the authenticated user to the specified receiver.",
     *     operationId="sendMessage",
     *     tags={"Messages"},
     *     security={{"bearer":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"receiver_id", "message"},
     *             @OA\Property(property="receiver_id", type="integer", example=2, description="ID of the user to receive the message"),
     *             @OA\Property(property="message", type="string", example="Hey! How are you?", description="The content of the message")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Message sent successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="message create successfully!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="sender_id", type="integer", example=1),
     *                 @OA\Property(property="receiver_id", type="integer", example=2),
     *                 @OA\Property(property="message", type="string", example="Hey! How are you?"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-17T10:20:30Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-17T10:20:30Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error or bad request",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="data", type="array", @OA\Items())
     *         )
     *     )
     * )
     */
    public function sendMessage(Request $request)
    {

        try {

            $message = Message::create([
                'sender_id'   => auth()->id(),
                'receiver_id' => $request->receiver_id,
                'message'     => $request->message,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'message create successfully!',
                'data' => $message
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
                'data' => []
            ], 200);
        }
    }
}
