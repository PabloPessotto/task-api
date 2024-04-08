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
    public function index()
    {
        $userId = Auth::guard('api')->user()->getAuthIdentifier();
        $labels = Label::where("userId", $userId)->get()->toArray();
        $message['text'] = 'All labels returned.';
        $message['status'] = 200;
        return response()->json(["content" => $labels, 'message' => $message]);
    }

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
