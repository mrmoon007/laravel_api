<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     operationId="Login",
     *     tags={"Admin"},
     *     summary="User Login",
     *     description="User Login here",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"email", "password"},
     *               @OA\Property(property="email", type="string", example="mostafijur@gmail.com"),
     *               @OA\Property(property="password", type="string", example="123456"),
     *            ),
     *        ),
     *        @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(
     *               type="object",
     *               required={"email", "password"},
     *               @OA\Property(property="email", type="string", example="mostafijur@gmail.com"),
     *               @OA\Property(property="password", type="string", example="123456"),
     *            ),
     *        ),
     *    ),
     *    @OA\Response(
     *        response=201,
     *        description="Login Successfully",
     *        @OA\JsonContent()
     *    ),
     *    @OA\Response(
     *        response=200,
     *        description="Login Successfully",
     *        @OA\JsonContent()
     *    ),
     *    @OA\Response(
     *        response=422,
     *        description="Unprocessable Entity",
     *        @OA\JsonContent()
     *    ),
     *    @OA\Response(response=400, description="Bad request"),
     *    @OA\Response(response=404, description="Resource Not Found"),
     * )
     */

     public function login(Request $request)
     {
         try {
             $validateUser = Validator::make(
                 $request->all(),
                 [
                     'email' => 'required|email',
                     'password' => 'required'
                 ]
             );
 
             if ($validateUser->fails()) {
                 return response()->json([
                     'status' => false,
                     'message' => 'validation error',
                     'errors' => $validateUser->errors()
                 ], 401);
             }
 
             if (!Auth::attempt($request->only(['email', 'password']))) {
                 return response()->json([
                     'status' => false,
                     'message' => 'Email & Password does not match with our record.',
                 ], 401);
             }
 
             $user = User::where('email', $request->email)->first();
 
             return response()->json([
                 'status' => true,
                 'message' => 'User Logged In Successfully',
                 'access_token' => $user->createToken("API TOKEN")->plainTextToken
             ], 200);
         } catch (\Throwable $th) {
             return response()->json([
                 'status' => false,
                 'message' => $th->getMessage()
             ], 500);
         }
     }
}
