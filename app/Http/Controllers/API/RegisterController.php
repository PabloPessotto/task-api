<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Info(
 *      title="User API",
 *      version="1.0.0",
 *      description="User Api Documentation"
 *  )
 */
class RegisterController extends Controller
{
    /** 
     *Register
     * @OA\Post (
     *     path="/api/register",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="name",
     *                          type="string"
     *                      ),
     *                   
     *                      @OA\Property(
     *                          property="password",
     *                          type="string"
     *                      )
     *                 ),
     *                 example={
     *                     "name":"John",
     *                     "password":"johnjohn1"
     *                }
     *             )
     *         )
     *      ),
     * )
     */
    public function register(Request $request)
    {
        $validatorUserName = Validator::make($request->all(), [
            'name' => 'required|exists:users,name'
        ]);

        if ($validatorUserName->passes()) {
            return response()->json(['message' => 'User exists.', 'content' => null], 400);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation errors.', 'content' => $validator->errors()], 400);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('MyApp')->accessToken;
        $success['name'] = $user->name;
        $message['text'] = 'User register successfully.';
        $message['status'] = 201;
        return response()->json(['message' => $message, 'content' => $success], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|exists:users,name',
            'password' => 'required',
        ]);

        if ($validator->passes()) {
            if (Auth::attempt(['name' => $request->name, 'password' => $request->password])) {
                $user = Auth::user();
                $success['userId'] = $user->id;
                $success['name'] = $user->name;
                $success['token'] = $user->createToken('MyApp')->accessToken;
                $message['text'] = 'User login successfully.';
                $message['status'] = 201;
                return response()->json(['message' => $message, 'content' => $success], 201);

            } else {
                $message['text'] = 'Incorrect password.';
                $message['status'] = 401;
                return response()->json(['message' => 'Incorrect password.', 'content' => null], 401);
            }
        } else {
            $message['text'] = 'User not found.';
            $message['status'] = 404;
            return response()->json(['message' => $message, 'content' => null], 400);
        }

    }
}
