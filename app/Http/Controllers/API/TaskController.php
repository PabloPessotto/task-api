<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Tasks;
use App\Models\Label;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{

    public function index()
    {
        $userId = Auth::guard('api')->user()->getAuthIdentifier();
        $tasks = Tasks::where("userId", $userId)->with('labels')->orderByRaw('-`order` desc')->orderBy('order', 'ASC')->get()->toArray();
        $message['text'] = 'All taks returned.';
        $message['status'] = 200;
        return response()->json(["content" => $tasks, 'message' => $message]);
    }

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
    public function update(Request $request, $id)
    {
        if (Tasks::where('id', $id)->exists()) {
            $task = Tasks::find($id);
            $task->title = is_null($request->title) ? $task->title : $request->title;
            $task->description = is_null($request->description) ? $task->description : $request->description;
            $task->status = is_null($request->status) ? $task->status : $request->status;
            $task->date = is_null($request->date) ? $task->date : $request->date;
            // $labels = $request->input('label');
            // if ($labels) {
            //     $task->label = $labels;
            // }

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

    public function updateOrder(Request $request, $id)
    {
        if (Tasks::where('id', $id)->exists()) {
            $task = Tasks::find($id);
            $task->order = is_null($request->order) ? $task->order : $request->order;
            $task->save();
            $maxIndex = $task->max('order');

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

    public function attachLabels(Tasks $task, Request $request)
    {
        $labels = Label::find($request->input('labels'));

        $task->labels()->attach($labels);
        $task->labels = $task->labels()->get();
        $message['text'] = 'Task label updated';
        $message['status'] = 200;
        return response()->json(['content' => $task, 'message' => $message]);
    }
    public function detachLabels(Tasks $task, $id)
    {
        $label = Label::find($id);
        $task->labels()->detach($label);
        return response()->json(['content' => true]);
    }

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
