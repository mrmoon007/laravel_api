<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * @OA\Post(
     * path="/api/register",
     * operationId="Register",
     * tags={"Admin"},
     * summary="User Register",
     * description="User Register here",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"name","email", "password", "password_confirmation"},
     *               @OA\Property(property="name", type="text", example="Mostafijur"),
     *               @OA\Property(property="email", type="text", example="mostafijur@gmail.com"),
     *               @OA\Property(property="password", type="password", example="123456"),
     *               @OA\Property(property="password_confirmation", type="password", example="123456")
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Register Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Register Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */

     public function register(Request $request)
     {
         try {
             //Validated
             $validateUser = Validator::make(
                 $request->all(),
                 [
                     'name' => 'required',
                     'email' => 'required|email|unique:users,email',
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
 
             $user = User::create([
                 'name' => $request->name,
                 'email' => $request->email,
                 'password' => Hash::make($request->password)
             ]);
 
             return response()->json([
                 'status' => true,
                 'message' => 'User Created Successfully',
                 'token' => $user->createToken("API TOKEN")->plainTextToken
             ], 200);
         } catch (\Throwable $th) {
             return response()->json([
                 'status' => false,
                 'message' => $th->getMessage()
             ], 500);
         }
     }
}
