<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="Get all users except the authenticated user",
     *     description="Returns a list of users excluding the currently authenticated user. Useful for messaging or listing other users.",
     *     operationId="getUsers",
     *     tags={"Users"},
     *     security={{"bearer":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Users fetched successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="message create successfully!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=2),
     *                     @OA\Property(property="name", type="string", example="Jane Doe"),
     *                     @OA\Property(property="email", type="string", example="jane@example.com"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-17T12:00:00Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-17T12:00:00Z")
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
    public function index()
    {
        try {

            $users = User::where('id', '!=', auth()->id())->latest('updated_at')->get();
            return response()->json([
                'status' => true,
                'message' => 'message create successfully!',
                'data' => $users
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
