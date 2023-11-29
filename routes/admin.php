<?php

use App\Http\Controllers\API\Admin\CarController;
use App\Http\Controllers\API\Admin\CommentController;
use App\Http\Controllers\API\Admin\DriverController;
use App\Http\Controllers\API\Admin\JobController;
use App\Http\Controllers\API\Admin\NewsController;
use App\Http\Controllers\API\Admin\PointController;
use App\Http\Controllers\API\Admin\SeatController;
use App\Http\Controllers\API\Admin\StationController;
use App\Http\Controllers\API\Admin\TicketController;
use App\Http\Controllers\API\Admin\timePointController;
use App\Http\Controllers\API\Admin\TripController;
use App\Http\Controllers\API\Admin\UserController;
use App\Models\Ticket;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'checkAdmin'])->group(function () {
  Route::apiResource('/car', CarController::class)->except(['update']);
  Route::get("/car/{id}/seat", [SeatController::class, "getByCar"]);
  Route::post("/car/{id}/update", [CarController::class, "update"]);
  Route::put("/car/seat/{seat}", [SeatController::class, "update"]);
  Route::delete("/car/seat/{seat}", [SeatController::class, "destroy"]);
  Route::post("/car/seat", [SeatController::class, "store"]);

  Route::get("/car/comment", [CommentController::class, "index"]);
  Route::put("/car/comment/{comment}", [CommentController::class, "update"]);
  Route::delete("/car/comment/{comment}", [CommentController::class, "destroy"]);

  Route::apiResource('/news', NewsController::class)->only(['store', 'destroy']);
  Route::post("/news/update/{id}", [NewsController::class, "update"]);

  Route::apiResource('/trip', TripController::class)->only(['store', 'destroy', 'update', 'index']);

  Route::apiResource('/station', StationController::class);
  Route::get("/station/{id}/point", [PointController::class, "getByStation"]);
  Route::post("/station/point", [PointController::class, "store"]);
  Route::put("/station/point/{point}", [PointController::class, "update"]);
  Route::delete("/station/point/{point}", [PointController::class, "destroy"]);

  Route::apiResource('/ticket', TicketController::class);
  Route::apiResource('/job', JobController::class)->only(['store', 'destroy', 'update']);;

  Route::apiResource('/user', UserController::class);
  Route::apiResource('/driver', DriverController::class);
});
