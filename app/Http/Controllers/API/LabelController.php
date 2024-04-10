<?php

namespace App\Http\Controllers\API;

use App\Models\Label;
use App\Models\TaskLabel;
use App\Models\Tasks;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LabelController extends Controller
{
    /**
     * Labels
     * @OA\Get (
     *     path="/api/labels",
     *     tags={"Label"},
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="object",
     *                  @OA\Property(property="code", type="number", example=200),
     *                  @OA\Property(property="status", type="string", example="success"),
     *                  @OA\Property(property="message", type="string", example=null),
     *              ),
     *              @OA\Property(property="content", type="array",collectionFormat="multi",
     *                    @OA\Items(
     *                    type="object",
     *                      @OA\Property(property="id", type="number", example=1),
     *                      @OA\Property(property="userId", type="number", example=1),
     *                      @OA\Property(property="created_at", type="string", example="2024-04-07T23:15:09.000000Z"),
     *                      @OA\Property(property="updated_at", type="string", example="2024-04-07T23:15:09.000000Z"),
     *                      @OA\Property(property="name", type="string", example="Label Name"),
     *                      @OA\Property(property="description", type="string", example="Label description"),
     *                      @OA\Property(property="color", type="string", example="#FFFFFF"),
     *                ),
     *              ),
     *          )
     *      ),
     *      security={
     *         {"token": {}}
     *     }
     * )
     */
    public function index()
    {
        $userId = Auth::guard('api')->user()->getAuthIdentifier();
        $labels = Label::where("userId", $userId)->get()->toArray();
        $message['text'] = 'All labels returned.';
        $message['status'] = 200;
        return response()->json(["content" => $labels, 'message' => $message]);
    }

    /**
     * Create new label
     * @OA\Post (
     *     path="/api/labels",
     *     tags={"Label"},
     * @OA\RequestBody(
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
     *                          property="description",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="color",
     *                          type="string"
     *                      ),
     *                 ),
     *                example={
     *                      "name" : "Urgente",
     *                      "description" : "Description",
     *                      "color" : "#FFFFFF",
     *                 }
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
     *              @OA\Property(property="content", type="object",collectionFormat="multi",
     *                  @OA\Property(property="id", type="number", example=1),
     *                      @OA\Property(property="userId", type="number", example=1),
     *                      @OA\Property(property="created_at", type="string", example="2024-04-07T23:15:09.000000Z"),
     *                      @OA\Property(property="updated_at", type="string", example="2024-04-07T23:15:09.000000Z"),
     *                      @OA\Property(property="name", type="string", example="Label Name"),
     *                      @OA\Property(property="description", type="string", example="Label description"),
     *                      @OA\Property(property="color", type="string", example="#FFFFFF"),             
     * ),
     *          )
     *      ),
     *      security={
     *         {"token": {}}
     *     }
     * )
     */
    public function store(Request $request)
    {
        $userId = Auth::guard('api')->user()->getAuthIdentifier();
        $label = new Label();
        $label->name = $request->name;
        $label->description = $request->description;
        $label->color = $request->color;
        $label->userId = $userId;
        $label->save();

        $labelCreated = Label::find($label->id);
        $message['text'] = 'New label created.';
        $message['status'] = 201;
        return response()->json(["message" => $message, "content" => $labelCreated], 201);

    }

    /**
     * Update label
     * @OA\Put (
     *     path="/api/labels/{id}",
     *     tags={"Label"},
     * @OA\Parameter(
     *    description="ID of label",
     *    in="path",
     *    name="id",
     *    required=true,
     *    example="1",
     *    @OA\Schema(
     *       type="integer",
     *       format="int64"
     *    )),
     * @OA\RequestBody(
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
     *                          property="description",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="color",
     *                          type="string"
     *                      ),
     *                 ),
     *                example={
     *                      "name" : "Urgente",
     *                      "description" : "Description",
     *                      "color" : "#FFFFFF",
     *                 }
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
     *              @OA\Property(property="content", type="object",collectionFormat="multi",
     *                  @OA\Property(property="id", type="number", example=1),
     *                      @OA\Property(property="userId", type="number", example=1),
     *                      @OA\Property(property="created_at", type="string", example="2024-04-07T23:15:09.000000Z"),
     *                      @OA\Property(property="updated_at", type="string", example="2024-04-07T23:15:09.000000Z"),
     *                      @OA\Property(property="name", type="string", example="Label Name"),
     *                      @OA\Property(property="description", type="string", example="Label description"),
     *                      @OA\Property(property="color", type="string", example="#FFFFFF"),             
     * ),
     *          )
     *      ),
     *      security={
     *         {"token": {}}
     *     }
     * )
     */
    public function update(Request $request, $id)
    {
        if (Label::where('id', $id)->exists()) {
            $label = label::find($id);
            $label->name = is_null($request->name) ? $label->name : $request->name;
            $label->description = is_null($request->description) ? $label->description : $request->description;
            $label->color = is_null($request->color) ? $label->color : $request->color;

            $label->save();
            $labelupdated = Label::find($id);
            $message['text'] = 'Label updated';
            $message['status'] = 201;
            return response()->json(['message' => $message, "content" => $labelupdated], 201);
        } else {
            $message['text'] = 'Label not found.';
            $message['status'] = 404;
            return response()->json(['message' => $message, 'content' => null], 404);
        }
    }

    /**
     * Delete label
     * @OA\Delete (
     *     path="/api/labels/{id}",
     *     tags={"Label"},
     * @OA\Parameter(
     *    description="ID of label",
     *    in="path",
     *    name="id",
     *    required=true,
     *    example="1",
     *    @OA\Schema(
     *       type="integer",
     *       format="int64"
     *    )),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="object",
     *                  @OA\Property(property="code", type="number", example=200),
     *                  @OA\Property(property="status", type="string", example="success"),
     *                  @OA\Property(property="message", type="string", example=null),
     *              ),
     *          )
     *      ),
     *      security={
     *         {"token": {}}
     *     }
     * )
     */
    public function destroy($id)
    {
        if (Label::where('id', $id)->exists()) {
            $label = Label::find($id);
            if ($label->tasks()->exists()) {
                $message['text'] = 'Label associated with tasks. Cannot delete.';
                $message['status'] = 400;
                return response()->json(['message' => $message, 'content' => null], 400);
            }
            $label->delete();
            $message['text'] = 'Label deleted.';
            $message['status'] = 202;
            return response()->json(['message' => $message, 'content' => null], 202);
        } else {
            $message['text'] = 'Label not found.';
            $message['status'] = 404;
            return response()->json(['message' => $message, 'content' => null], 404);
        }
    }
}
