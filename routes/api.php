<?php
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\TaskController;

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::resource('tasks', TaskController::class);

    Route::get("/tasks", [TaskController::class, "index"]);
    Route::get("/tasks/{id}", [TaskController::class, "show"]);
    Route::post("/tasks", [TaskController::class, "store"]);
    Route::put("/tasks/{id}", [TaskController::class, "update"]);
    Route::put("/tasks/{id}/status", [TaskController::class, "updateStatus"]);
    Route::put("/tasks/{id}/index", [TaskController::class, "updateIndex"]);
    Route::delete("/tasks/{id}", [TaskController::class, "destroy"]);
});

