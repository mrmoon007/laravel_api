<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\GroupMessage;
use Exception;
use Illuminate\Http\Request;

class GroupChatController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/groups/create",
     *     summary="Create a new chat group",
     *     description="Creates a new chat group and adds the authenticated user as the admin.",
     *     operationId="createGroup",
     *     tags={"Groups"},
     *     security={{"bearer":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "description"},
     *             @OA\Property(property="name", type="string", example="Project Team", description="The name of the group"),
     *             @OA\Property(property="description", type="string", example="Group for project collaboration", description="The description of the group")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Group created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Group create successfully!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Project Team"),
     *                 @OA\Property(property="description", type="string", example="Group for project collaboration"),
     *                 @OA\Property(property="created_by", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-17T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-17T12:00:00Z")
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
    public function createGroup(Request $request)
    {

        try {

            $group = Group::create([
                'name'       => $request->name,
                'description' => $request->description,
                'created_by' => auth()->id()
            ]);

            $group->users()->attach(auth()->id(), ['role' => 'admin']);

            return response()->json([
                'status' => true,
                'message' => 'Group create successfully!',
                'data' => $group
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
     * @OA\Get(
     *     path="/api/groups",
     *     summary="Get all groups the authenticated user belongs to",
     *     description="Returns a list of groups where the authenticated user is a member.",
     *     operationId="myGroups",
     *     tags={"Groups"},
     *     security={{"bearer":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="User's groups fetched successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User groups!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Project Team"),
     *                     @OA\Property(property="description", type="string", example="Group for project collaboration"),
     *                     @OA\Property(property="created_by", type="integer", example=1),
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
    public function myGroups()
    {
        try {

            $group = auth()->user()->groups;

            return response()->json([
                'status' => true,
                'message' => 'User groups!',
                'data' => $group
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
     *     path="/api/groups/{group}/messages/send",
     *     summary="Send a message to a group",
     *     description="Sends a message from the authenticated user to a specified group. User must be a member of the group.",
     *     operationId="sendGroupMessage",
     *     tags={"Groups"},
     *     security={{"bearer":{}}},
     *
     *     @OA\Parameter(
     *         name="group",
     *         in="path",
     *         required=true,
     *         description="ID of the group",
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"message"},
     *             @OA\Property(property="message", type="string", example="Hello team!", description="Message to be sent to the group")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Group message sent successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Group messages!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="group_id", type="integer", example=1),
     *                 @OA\Property(property="sender_id", type="integer", example=2),
     *                 @OA\Property(property="message", type="string", example="Hello team!"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-17T14:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-17T14:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="User not authorized to send message to this group",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="You are not authorized to access this group."),
     *             @OA\Property(property="data", type="array", @OA\Items())
     *         )
     *     )
     * )
     */
    public function sendGroupMessage(Request $request, Group $group)
    {

        try {

            if (!$group->users->contains(auth()->id())) {
                throw new Exception('You are not authorized to access this group.');
            }

            $groupMessage = GroupMessage::create([
                'group_id' => $group->id,
                'sender_id' => auth()->id(),
                'message'  => $request->message,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Group messages!',
                'data' => $groupMessage
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
     * @OA\Get(
     *     path="/api/groups/{group}/messages",
     *     summary="Get all messages from a group",
     *     description="Retrieves all messages from the specified group. The authenticated user must be a member of the group.",
     *     operationId="getGroupMessages",
     *     tags={"Groups"},
     *     security={{"bearer":{}}},
     *
     *     @OA\Parameter(
     *         name="group",
     *         in="path",
     *         required=true,
     *         description="ID of the group",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Group messages retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Group messages!"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="group_id", type="integer", example=1),
     *                     @OA\Property(property="sender_id", type="integer", example=2),
     *                     @OA\Property(property="message", type="string", example="Hey everyone!"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-17T14:00:00Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-17T14:00:00Z")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="User not authorized to access this group",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="You are not authorized to access this group."),
     *             @OA\Property(property="data", type="array", @OA\Items())
     *         )
     *     )
     * )
     */
    public function getGroupMessages(Group $group)
    {

        try {

            if (!$group->users->contains(auth()->id())) {
                throw new Exception('You are not authorized to access this group.');
            }

            $groupMessage = $group->messages()->orderBy('created_at')->get();

            return response()->json([
                'status' => true,
                'message' => 'Group messages!',
                'data' => $groupMessage
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
