<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Tasks;
use App\Http\Controllers\Controller;

class TaskController extends Controller
{

    public function index()
    {
        $tasks = Tasks::all();
        $message['text'] = 'All taks returned.';
        $message['status'] = 200;
        return response()->json(["content" => $tasks, 'message' => $message]);
    }

    public function store(Request $request)
    {
        $tasks = new Tasks();
        $tasks->title = $request->title;
        $tasks->description = $request->description;
        $tasks->status = $request->status;
        $tasks->userId = $request->userId;
        $tasks->date = $request->date;

        $labels = $request->input('label');
        if ($labels) {
            $tasks->label = json_encode($labels);
        }


        $tasks->save();
        $task = Tasks::find($tasks->id);
        $message['text'] = 'New task created.';
        $message['status'] = 201;
        return response()->json(["message" => $message, "content" => $task], 201);
    }

    public function show($id)
    {
        $task = Tasks::find($id);
        if (!empty($task)) {
            $message['text'] = 'Task returned.';
            $message['status'] = 200;
            return response()->json(["content" => $task, "message" => $message]);
        } else {
            $message['text'] = 'Task not found.';
            $message['status'] = 404;
            return response()->json(["message" => $message, 'content' => null], 404);
        }
    }
    public function update(Request $request, $id)
    {
        if (Tasks::where('id', $id)->exists()) {
            $task = Tasks::find($id);
            $task->title = is_null($request->title) ? $task->title : $request->title;
            $task->description = is_null($request->description) ? $task->description : $request->description;
            $task->status = is_null($request->status) ? $task->status : $request->status;
            $task->date = is_null($request->date) ? $task->date : $request->date;
            $labels = $request->input('label');
            if ($labels) {
                $task->label = $labels;
            }
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

    public function updateStatus(Request $request, $id)
    {
        if (Tasks::where('id', $id)->exists()) {
            $task = Tasks::find($id);
            $task->status = is_null($request->status) ? $task->status : $request->status;
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
    public function destroy($id)
    {
        if (Tasks::where('id', $id)->exists()) {
            $task = Tasks::find($id);
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
}
