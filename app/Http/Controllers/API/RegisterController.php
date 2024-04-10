<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Info(
 *      title="tarefas for me",
 *      version="1.0.0",
 *      description="APIs são baseadas na arquitetura REST e usam os métodos básicos de solicitação HTTP. Para melhor compreensão, os recursos são listados juntamente com seus parâmetros obrigatórios, se houver, e seus exemplos de solicitações e respostas logo abaixo da descrição. As APIs aceitam solicitações de corpo codificado em JSON e retornam dados da mesma forma."
 *  )
 */
class RegisterController extends Controller
{
    /**
     * Register
     * @OA\Post (
     *     path="/api/register",
     *     tags={"User"},
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
     *                      @OA\Property(
     *                          property="password",
     *                          type="string"
     *                      )
     *                 ),
     *                 example={
     *                     "name":"John550",
     *                     "password":"johnjohn1"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="object",
     *                  @OA\Property(property="code", type="number", example=200),
     *                  @OA\Property(property="status", type="string", example="success"),
     *                  @OA\Property(property="message", type="string", example=null),
     *              ),
     *              @OA\Property(property="content", type="object",
     *                   @OA\Property(property="userId", type="number", example=1),
     *                   @OA\Property(property="name", type="string", example="John550"),
     *                   @OA\Property(property="token", type="string", example="randomtokenasfhajskfhajf398rureuuhfdshk"),
     *              ),
     *          )
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

    /**
     * Login
     * @OA\Post (
     *     path="/api/login",
     *     tags={"User"},
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
     *                      @OA\Property(
     *                          property="password",
     *                          type="string"
     *                      )
     *                 ),
     *                 example={
     *                     "name":"John550",
     *                     "password":"johnjohn1"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="object",
     *                  @OA\Property(property="code", type="number", example=200),
     *                  @OA\Property(property="status", type="string", example="success"),
     *                  @OA\Property(property="message", type="string", example=null),
     *              ),
     *              @OA\Property(property="content", type="object",
     *                   @OA\Property(property="userId", type="number", example=1),
     *                   @OA\Property(property="name", type="string", example="John550"),
     *                   @OA\Property(property="token", type="string", example="randomtokenasfhajskfhajf398rureuuhfdshk"),
     *              ),
     *          )
     *      ),
     * )
     */
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
