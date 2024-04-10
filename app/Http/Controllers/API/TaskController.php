<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Tasks;
use App\Models\Label;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\SecurityScheme(
 *      type="oauth2",
 *     securityScheme="token",
 *     name="Authorization"
 * )
 */
class TaskController extends Controller
{
    /**
     * Tasks
     * @OA\Get (
     *     path="/api/tasks",
     *     tags={"Task"},
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="object",
     *                  @OA\Property(property="code", type="number", example=200),
     *                  @OA\Property(property="status", type="string", example="success"),
     *                  @OA\Property(property="message", type="string", example=null),
     *              ),
     *              @OA\Property(property="content", type="array",
     *                @OA\Items(
     *                type="object",
     *                  @OA\Property(property="id", type="number", example=1),
     *                   @OA\Property(property="created_at", type="string", example="2024-04-07T23:15:09.000000Z"),
     *                   @OA\Property(property="updated_at", type="string", example="2024-04-07T23:15:09.000000Z"),
     *                   @OA\Property(property="title", type="string", example="Task Title"),
     *                   @OA\Property(property="description", type="string", example="Task Description"),
     *                   @OA\Property(property="status", type="string", example="OPENED"),
     *                   @OA\Property(property="userId", type="number", example=1),
     *                   @OA\Property(property="date", type="string", example="07-04-2024"),
     *                   @OA\Property(property="order", type="number", example=0),
     *                   @OA\Property(property="labels", type="array",collectionFormat="multi",
     *                    @OA\Items(
     *                    type="object",
     *                      @OA\Property(property="id", type="number", example=1),
     *                      @OA\Property(property="userId", type="number", example=1),
     *                      @OA\Property(property="created_at", type="string", example="2024-04-07T23:15:09.000000Z"),
     *                      @OA\Property(property="updated_at", type="string", example="2024-04-07T23:15:09.000000Z"),
     *                      @OA\Property(property="name", type="string", example="Label Name"),
     *                      @OA\Property(property="description", type="string", example="Label description"),
     *                      @OA\Property(property="color", type="string", example="#FFFFFF"),
     *                    ),
     *
     *                   ),
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
        $tasks = Tasks::where("userId", $userId)->with('labels')->orderByRaw('-`order` desc')->orderBy('order', 'ASC')->get()->toArray();
        $message['text'] = 'All taks returned.';
        $message['status'] = 200;
        return response()->json(["content" => $tasks, 'message' => $message]);
    }

    /**
     * Register task
     * @OA\Post (
     *     path="/api/tasks",
     *     tags={"Task"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="title",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="description",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="status",
     *                          type="string"
     *                      ),
     *                       @OA\Property(
     *                          property="labels",
     *                          type="array",
     *                          collectionFormat="multi",
     *                          @OA\Items(
     *                          type="number",
     *                          @OA\Property(type="number", example=1),
     *                          )
     *                      ),
     *
     *                 ),
     *                example={
     *                      "title" : "Title ",
     *                      "description" : "Description",
     *                      "status" : "OPENED",
     *                      "date" : "07-04-2024",
     *                      "labels" : {1, 3}
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
     *              @OA\Property(property="content", type="object",
     *                   @OA\Property(property="id", type="number", example=1),
     *                   @OA\Property(property="created_at", type="string", example="2024-04-07T23:15:09.000000Z"),
     *                   @OA\Property(property="updated_at", type="string", example="2024-04-07T23:15:09.000000Z"),
     *                   @OA\Property(property="title", type="string", example="Task Title"),
     *                   @OA\Property(property="description", type="string", example="Task Description"),
     *                   @OA\Property(property="status", type="string", example="OPENED"),
     *                   @OA\Property(property="userId", type="number", example=1),
     *                   @OA\Property(property="date", type="string", example="07-04-2024"),
     *                   @OA\Property(property="order", type="number", example=0),
     *                   @OA\Property(property="labels", type="array",collectionFormat="multi",
     *                    @OA\Items(
     *                    type="object",
     *                      @OA\Property(property="id", type="number", example=1),
     *                      @OA\Property(property="userId", type="number", example=1),
     *                      @OA\Property(property="created_at", type="string", example="2024-04-07T23:15:09.000000Z"),
     *                      @OA\Property(property="updated_at", type="string", example="2024-04-07T23:15:09.000000Z"),
     *                      @OA\Property(property="name", type="string", example="Label Name"),
     *                      @OA\Property(property="description", type="string", example="Label description"),
     *                      @OA\Property(property="color", type="string", example="#FFFFFF"),
     *                    ),
     *
     *                   ),
     *              ),
     *          )
     *      ),
     *  security={
     *         {"token": {}}
     *     }
     * )
     */
    public function store(Request $request)
    {
        $userId = Auth::guard('api')->user()->getAuthIdentifier();
        $tasks = new Tasks();
        $tasks->title = $request->title;
        $tasks->description = $request->description;
        $tasks->status = $request->status;
        $tasks->userId = $userId;
        $tasks->date = $request->date;

        $tasks->save();

        $task = Tasks::find($tasks->id);
        $labels = Label::find($request->input('labels'));
        $task->labels()->attach($labels);

        $task->labels = $task->labels()->get();

        $message['text'] = 'New task created.';
        $message['status'] = 201;
        return response()->json(["message" => $message, "content" => $task], 201);
    }

    /**
     * Get task by id
     * @OA\Get (
     *     path="/api/tasks/{id}",
     *     tags={"Task"},
     * @OA\Parameter(
     *    description="ID of Task",
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
     *              @OA\Property(property="content", type="object",
     *                   @OA\Property(property="id", type="number", example=1),
     *                   @OA\Property(property="created_at", type="string", example="2024-04-07T23:15:09.000000Z"),
     *                   @OA\Property(property="updated_at", type="string", example="2024-04-07T23:15:09.000000Z"),
     *                   @OA\Property(property="title", type="string", example="Task Title"),
     *                   @OA\Property(property="description", type="string", example="Task Description"),
     *                   @OA\Property(property="status", type="string", example="OPENED"),
     *                   @OA\Property(property="userId", type="number", example=1),
     *                   @OA\Property(property="date", type="string", example="07-04-2024"),
     *                   @OA\Property(property="order", type="number", example=0),
     *                   @OA\Property(property="labels", type="array",collectionFormat="multi",
     *                    @OA\Items(
     *                    type="object",
     *                      @OA\Property(property="id", type="number", example=1),
     *                      @OA\Property(property="userId", type="number", example=1),
     *                      @OA\Property(property="created_at", type="string", example="2024-04-07T23:15:09.000000Z"),
     *                      @OA\Property(property="updated_at", type="string", example="2024-04-07T23:15:09.000000Z"),
     *                      @OA\Property(property="name", type="string", example="Label Name"),
     *                      @OA\Property(property="description", type="string", example="Label description"),
     *                      @OA\Property(property="color", type="string", example="#FFFFFF"),
     *                    ),
     *
     *                   ),
     *              ),
     *          )
     *      ),
     *  security={
     *         {"token": {}}
     *     }
     * )
     */
    public function show($id)
    {
        $task = Tasks::find($id);
        if (!empty($task)) {
            $message['text'] = 'Task returned.';
            $message['status'] = 200;
            // print ($task->labels()->get());
            $task->labels = $task->labels()->get();
            return response()->json(["content" => $task, "message" => $message]);
        } else {
            $message['text'] = 'Task not found.';
            $message['status'] = 404;
            return response()->json(["message" => $message, 'content' => null], 404);
        }

    }
    /**
     * Update task
     * @OA\Put (
     *     path="/api/tasks/{id}",
     *     tags={"Task"},
     * @OA\Parameter(
     *    description="ID of Task",
     *    in="path",
     *    name="id",
     *    required=true,
     *    example="1",
     *    @OA\Schema(
     *       type="integer",
     *       format="int64"
     *    )),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="title",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="description",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="status",
     *                          type="string"
     *                      ),
     *                       @OA\Property(
     *                          property="labels",
     *                          type="array",
     *                          @OA\Items(
     *                          type="number",
     *                          example={"1,2,5"},
     *                          )
     *                      ),
     *
     *                 ),
     *                example={
     *                      "title" : "Title",
     *                     "description" : "Description",
     *                    "status" : "OPENED",
     *                   "date" : "07-04-2024",
     *                  "labels": {1,5,2}
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
     *              @OA\Property(property="content", type="object",
     *                   @OA\Property(property="id", type="number", example=1),
     *                   @OA\Property(property="created_at", type="string", example="2024-04-07T23:15:09.000000Z"),
     *                   @OA\Property(property="updated_at", type="string", example="2024-04-07T23:15:09.000000Z"),
     *                   @OA\Property(property="title", type="string", example="Task Title"),
     *                   @OA\Property(property="description", type="string", example="Task Description"),
     *                   @OA\Property(property="status", type="string", example="OPENED"),
     *                   @OA\Property(property="userId", type="number", example=1),
     *                   @OA\Property(property="date", type="string", example="07-04-2024"),
     *                   @OA\Property(property="order", type="number", example=0),
     *                   @OA\Property(property="labels", type="array",collectionFormat="multi",
     *                    @OA\Items(
     *                    type="object",
     *                      @OA\Property(property="id", type="number", example=1),
     *                      @OA\Property(property="userId", type="number", example=1),
     *                      @OA\Property(property="created_at", type="string", example="2024-04-07T23:15:09.000000Z"),
     *                      @OA\Property(property="updated_at", type="string", example="2024-04-07T23:15:09.000000Z"),
     *                      @OA\Property(property="name", type="string", example="Label Name"),
     *                      @OA\Property(property="description", type="string", example="Label description"),
     *                      @OA\Property(property="color", type="string", example="#FFFFFF"),
     *                    ),
     *
     *                   ),
     *              ),
     *          )
     *      ),
     *  security={
     *         {"token": {}}
     *     }
     * )
     */
    public function update(Request $request, $id)
    {
        if (Tasks::where('id', $id)->exists()) {
            $task = Tasks::find($id);
            $task->title = is_null($request->title) ? $task->title : $request->title;
            $task->description = is_null($request->description) ? $task->description : $request->description;
            $task->status = is_null($request->status) ? $task->status : $request->status;
            $task->date = is_null($request->date) ? $task->date : $request->date;

            $labels = Label::find($request->input('labels'));
            $task->labels()->sync($labels);

            $message['text'] = 'Task label updated.';
            $message['status'] = 201;

            $task->labels = $task->labels()->get();

            $task->save();
            $taskUpdated = Tasks::find($id);
            $message['text'] = 'Task updated';
            $message['status'] = 201;
            return response()->json(['message' => $message, "content" => $taskUpdated], 201);
        } else {
            $message['text'] = 'Task not found.';
            $message['status'] = 404;
            return response()->json(['message' => $message, 'content' => null], 404);
        }
    }

    /**
     * Update task status
     * @OA\Put (
     *     path="/api/tasks/{id}/status",
     *     tags={"Task"},
     * @OA\Parameter(
     *    description="ID of Task",
     *    in="path",
     *    name="id",
     *    required=true,
     *    example="1",
     *    @OA\Schema(
     *       type="integer",
     *       format="int64"
     *    )),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="status",
     *                          type="string"
     *                      ),
     *
     *                 ),
     *                example={
     *                    "status" : "DOING",
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
     *              @OA\Property(property="content", type="object",
     *                   @OA\Property(property="id", type="number", example=1),
     *                   @OA\Property(property="created_at", type="string", example="2024-04-07T23:15:09.000000Z"),
     *                   @OA\Property(property="updated_at", type="string", example="2024-04-07T23:15:09.000000Z"),
     *                   @OA\Property(property="title", type="string", example="Task Title"),
     *                   @OA\Property(property="description", type="string", example="Task Description"),
     *                   @OA\Property(property="status", type="string", example="DOING"),
     *                   @OA\Property(property="userId", type="number", example=1),
     *                   @OA\Property(property="date", type="string", example="07-04-2024"),
     *                   @OA\Property(property="order", type="number", example=0),
     *                   @OA\Property(property="labels", type="array",collectionFormat="multi",
     *                    @OA\Items(
     *                    type="object",
     *                      @OA\Property(property="id", type="number", example=1),
     *                      @OA\Property(property="userId", type="number", example=1),
     *                      @OA\Property(property="created_at", type="string", example="2024-04-07T23:15:09.000000Z"),
     *                      @OA\Property(property="updated_at", type="string", example="2024-04-07T23:15:09.000000Z"),
     *                      @OA\Property(property="name", type="string", example="Label Name"),
     *                      @OA\Property(property="description", type="string", example="Label description"),
     *                      @OA\Property(property="color", type="string", example="#FFFFFF"),
     *                    ),
     *
     *                   ),
     *              ),
     *          )
     *      ),
     *  security={
     *         {"token": {}}
     *     }
     * )
     */
    public function updateStatus(Request $request, $id)
    {
        if (Tasks::where('id', $id)->exists()) {
            $task = Tasks::find($id);
            $task->status = is_null($request->status) ? $task->status : $request->status;
            $task->save();
            $taskUpdated = Tasks::find($id);
            $taskUpdated->labels = $taskUpdated->labels()->get();
            $message['text'] = 'Task updated';
            $message['status'] = 201;
            return response()->json(['message' => $message, "content" => $taskUpdated], 201);
        } else {
            $message['text'] = 'Task not found.';
            $message['status'] = 404;
            return response()->json(['message' => $message, 'content' => null], 404);
        }
    }

    /**
     * Update task order
     * @OA\Put (
     *     path="/api/tasks/{id}/order",
     *     tags={"Task"},
     * @OA\Parameter(
     *    description="ID of Task",
     *    in="path",
     *    name="id",
     *    required=true,
     *    example="1",
     *    @OA\Schema(
     *       type="integer",
     *       format="int64"
     *    )),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="order",
     *                          type="number"
     *                      ),
     *
     *                 ),
     *                example={
     *                    "order" : 0,
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
     *              @OA\Property(property="content", type="object",
     *                   @OA\Property(property="id", type="number", example=1),
     *                   @OA\Property(property="created_at", type="string", example="2024-04-07T23:15:09.000000Z"),
     *                   @OA\Property(property="updated_at", type="string", example="2024-04-07T23:15:09.000000Z"),
     *                   @OA\Property(property="title", type="string", example="Task Title"),
     *                   @OA\Property(property="description", type="string", example="Task Description"),
     *                   @OA\Property(property="status", type="string", example="OPENED"),
     *                   @OA\Property(property="userId", type="number", example=1),
     *                   @OA\Property(property="date", type="string", example="07-04-2024"),
     *                   @OA\Property(property="order", type="number", example=0),
     *                   @OA\Property(property="labels", type="array",collectionFormat="multi",
     *                    @OA\Items(
     *                    type="object",
     *                      @OA\Property(property="id", type="number", example=1),
     *                      @OA\Property(property="userId", type="number", example=1),
     *                      @OA\Property(property="created_at", type="string", example="2024-04-07T23:15:09.000000Z"),
     *                      @OA\Property(property="updated_at", type="string", example="2024-04-07T23:15:09.000000Z"),
     *                      @OA\Property(property="name", type="string", example="Label Name"),
     *                      @OA\Property(property="description", type="string", example="Label description"),
     *                      @OA\Property(property="color", type="string", example="#FFFFFF"),
     *                    ),
     *
     *                   ),
     *              ),
     *          )
     *      ),
     *  security={
     *         {"token": {}}
     *     }
     * )
     */
    public function updateOrder(Request $request, $id)
    {
        if (Tasks::where('id', $id)->exists()) {
            $task = Tasks::find($id);
            $task->order = is_null($request->order) ? $task->order : $request->order;
            $task->save();
            $maxIndex = $task->max('order');

            $allTasks = Tasks::where('id', '!=', $id)->get();
            foreach ($allTasks as $tasks) {
                if ($tasks->order >= $request->order && $tasks->order == $task->order) {
                    $tasks->order = $tasks->order + 1;
                } else
                    if ($tasks->order < $request->order && $tasks->order != null) {
                        if ($tasks->order <= 0) {
                            $tasks->order = 0;
                        } else {
                            $tasks->order = $tasks->order - 1;
                        }
                    }
                $tasks->save();
            }

            $taskUpdated = Tasks::find($id);
            $taskUpdated->labels = $taskUpdated->labels()->get();

            $message['text'] = 'Task index updated';
            $message['status'] = 201;
            return response()->json(['message' => $message, "content" => $taskUpdated], 201);
        } else {
            $message['text'] = 'Task not found.';
            $message['status'] = 404;
            return response()->json(['message' => $message, 'content' => null], 404);
        }
    }

    /**
     * Delete task
     * @OA\Delete (
     *     path="/api/tasks/{id}",
     *     tags={"Task"},
     * @OA\Parameter(
     *    description="ID of Task",
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
     *  security={
     *         {"token": {}}
     *     }
     * )
     */
    public function destroy($id)
    {
        if (Tasks::where('id', $id)->exists()) {
            $task = Tasks::find($id);
            if ($task->labels()->exists()) {
                $label = $task->labels()->get();
                $task->labels()->detach($label);
            }
            $task->delete();
            $message['text'] = 'Task deleted';
            $message['status'] = 202;
            return response()->json(['message' => $message, 'content' => null], 202);
        } else {
            $message['text'] = 'Task not found.';
            $message['status'] = 404;
            return response()->json(['message' => $message, 'content' => null], 404);
        }
    }

    /**
     * Create link task labels
     * @OA\Post (
     *     path="/task/{task}/labels",
     *     tags={"Task"},
     * @OA\Parameter(
     *    description="ID of Task",
     *    in="path",
     *    name="task",
     *    required=true,
     *    example="1",
     *    @OA\Schema(
     *       type="integer",
     *       format="int64"
     *    )),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  @OA\Property(
     *                          property="labels",
     *                          type="array",
     *                          collectionFormat="multi",
     *                          @OA\Items(
     *                          type="number",
     *                          @OA\Property(type="number", example=1),
     *                          )
     *                      ),
     *                example={
     *                    "labels" : {4,5,6}
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
     *              @OA\Property(property="content", type="object",
     *                   @OA\Property(property="id", type="number", example=1),
     *                   @OA\Property(property="created_at", type="string", example="2024-04-07T23:15:09.000000Z"),
     *                   @OA\Property(property="updated_at", type="string", example="2024-04-07T23:15:09.000000Z"),
     *                   @OA\Property(property="title", type="string", example="Task Title"),
     *                   @OA\Property(property="description", type="string", example="Task Description"),
     *                   @OA\Property(property="status", type="string", example="OPENED"),
     *                   @OA\Property(property="userId", type="number", example=1),
     *                   @OA\Property(property="date", type="string", example="07-04-2024"),
     *                   @OA\Property(property="order", type="number", example=0),
     *                   @OA\Property(property="labels", type="array",collectionFormat="multi",
     *                    @OA\Items(
     *                    type="object",
     *                      @OA\Property(property="id", type="number", example=1),
     *                      @OA\Property(property="userId", type="number", example=1),
     *                      @OA\Property(property="created_at", type="string", example="2024-04-07T23:15:09.000000Z"),
     *                      @OA\Property(property="updated_at", type="string", example="2024-04-07T23:15:09.000000Z"),
     *                      @OA\Property(property="name", type="string", example="Label Name"),
     *                      @OA\Property(property="description", type="string", example="Label description"),
     *                      @OA\Property(property="color", type="string", example="#FFFFFF"),
     *                    ),
     *
     *                   ),
     *              ),
     *          )
     *      ),
     *  security={
     *         {"token": {}}
     *     }
     * )
     */
    public function attachLabels(Tasks $task, Request $request)
    {
        $labels = Label::find($request->input('labels'));

        $task->labels()->attach($labels);
        $task->labels = $task->labels()->get();
        $message['text'] = 'Task label updated';
        $message['status'] = 200;
        return response()->json(['content' => $task, 'message' => $message]);
    }

    /**
     * Remove label from task
     * @OA\Delete (
     *     path="/task/{task}/labels/{id}",
     *     tags={"Task"},
     * @OA\Parameter(
     *    description="ID of Task",
     *    in="path",
     *    name="task",
     *    required=true,
     *    example="1",
     *    @OA\Schema(
     *       type="integer",
     *       format="int64"
     *    )),
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
     *              @OA\Property(property="content", type="object",
     *                   @OA\Property(property="id", type="number", example=1),
     *                   @OA\Property(property="created_at", type="string", example="2024-04-07T23:15:09.000000Z"),
     *                   @OA\Property(property="updated_at", type="string", example="2024-04-07T23:15:09.000000Z"),
     *                   @OA\Property(property="title", type="string", example="Task Title"),
     *                   @OA\Property(property="description", type="string", example="Task Description"),
     *                   @OA\Property(property="status", type="string", example="OPENED"),
     *                   @OA\Property(property="userId", type="number", example=1),
     *                   @OA\Property(property="date", type="string", example="07-04-2024"),
     *                   @OA\Property(property="order", type="number", example=0),
     *                   @OA\Property(property="labels", type="array",collectionFormat="multi",
     *                    @OA\Items(
     *                    type="object",
     *                      @OA\Property(property="id", type="number", example=1),
     *                      @OA\Property(property="userId", type="number", example=1),
     *                      @OA\Property(property="created_at", type="string", example="2024-04-07T23:15:09.000000Z"),
     *                      @OA\Property(property="updated_at", type="string", example="2024-04-07T23:15:09.000000Z"),
     *                      @OA\Property(property="name", type="string", example="Label Name"),
     *                      @OA\Property(property="description", type="string", example="Label description"),
     *                      @OA\Property(property="color", type="string", example="#FFFFFF"),
     *                    ),
     *
     *                   ),
     *              ),
     *          )
     *      ),
     *  security={
     *         {"token": {}}
     *     }
     * )
     */
    public function detachLabels(Tasks $task, $id)
    {
        $label = Label::find($id);
        $task->labels()->detach($label);
        return response()->json(['content' => true]);
    }

    /**
     * Update task labels
     * @OA\Put (
     *     path="/task/{task}/labels",
     *     tags={"Task"},
     * @OA\Parameter(
     *    description="ID of Task",
     *    in="path",
     *    name="task",
     *    required=true,
     *    example="1",
     *    @OA\Schema(
     *       type="integer",
     *       format="int64"
     *    )),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  @OA\Property(
     *                          property="labels",
     *                          type="array",
     *                          collectionFormat="multi",
     *                          @OA\Items(
     *                          type="number",
     *                          @OA\Property(type="number", example=1),
     *                          )
     *                      ),
     *                example={
     *                    "labels" : {4,5,6}
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
     *              @OA\Property(property="content", type="object",
     *                   @OA\Property(property="id", type="number", example=1),
     *                   @OA\Property(property="created_at", type="string", example="2024-04-07T23:15:09.000000Z"),
     *                   @OA\Property(property="updated_at", type="string", example="2024-04-07T23:15:09.000000Z"),
     *                   @OA\Property(property="title", type="string", example="Task Title"),
     *                   @OA\Property(property="description", type="string", example="Task Description"),
     *                   @OA\Property(property="status", type="string", example="OPENED"),
     *                   @OA\Property(property="userId", type="number", example=1),
     *                   @OA\Property(property="date", type="string", example="07-04-2024"),
     *                   @OA\Property(property="order", type="number", example=0),
     *                   @OA\Property(property="labels", type="array",collectionFormat="multi",
     *                    @OA\Items(
     *                    type="object",
     *                      @OA\Property(property="id", type="number", example=1),
     *                      @OA\Property(property="userId", type="number", example=1),
     *                      @OA\Property(property="created_at", type="string", example="2024-04-07T23:15:09.000000Z"),
     *                      @OA\Property(property="updated_at", type="string", example="2024-04-07T23:15:09.000000Z"),
     *                      @OA\Property(property="name", type="string", example="Label Name"),
     *                      @OA\Property(property="description", type="string", example="Label description"),
     *                      @OA\Property(property="color", type="string", example="#FFFFFF"),
     *                    ),
     *
     *                   ),
     *              ),
     *          )
     *      ),
     *  security={
     *         {"token": {}}
     *     }
     * )
     */
    public function updateLabels(Tasks $task, Request $request)
    {
        $labels = Label::find($request->input('labels'));
        $task->labels()->sync($labels);

        $message['text'] = 'Task label updated.';
        $message['status'] = 201;

        $task->labels = $task->labels()->get();
        return response()->json(["content" => $task, "message" => $message]);


    }
}
