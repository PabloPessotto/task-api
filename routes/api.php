<?php
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\TaskController;
use App\Http\Controllers\API\LabelController;

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::resource('tasks', TaskController::class);

    Route::get("/tasks", [TaskController::class, "index"]);
    Route::get("/tasks/{id}", [TaskController::class, "show"]);
    Route::post("/tasks", [TaskController::class, "store"]);
    Route::put("/tasks/{id}", [TaskController::class, "update"]);
    Route::put("/tasks/{id}/status", [TaskController::class, "updateStatus"]);
    Route::put("/tasks/{id}/order", [TaskController::class, "updateOrder"]);
    Route::delete("/tasks/{id}", [TaskController::class, "destroy"]);

    Route::post("/label", [LabelController::class, "store"]);
    Route::get("/labels", [LabelController::class, "index"]);
    Route::put("/label/{id}", [LabelController::class, "update"]);
    Route::delete("/label/{id}", [LabelController::class, "destroy"]);

    Route::post("/task/{task}/labels", [TaskController::class, "attachLabels"]);
    Route::delete("/task/{task}/labels/{id}", [TaskController::class, "detachLabels"]);
    Route::put("/task/{task}/labels", [TaskController::class, "updateLabels"]);
});

