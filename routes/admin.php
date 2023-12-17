<?php

use App\Http\Controllers\API\Admin\CarController;
use App\Http\Controllers\API\Admin\CommentController;
use App\Http\Controllers\API\Admin\DriverController;
use App\Http\Controllers\API\Admin\JobController;
use App\Http\Controllers\API\Admin\NewsController;
use App\Http\Controllers\API\Admin\PointController;
use App\Http\Controllers\API\Admin\SeatController;
use App\Http\Controllers\API\Admin\StationController;
use App\Http\Controllers\API\Admin\StatisticController;
use App\Http\Controllers\API\Admin\TicketController;
use App\Http\Controllers\API\Admin\timePointController;
use App\Http\Controllers\API\Admin\TripController;
use App\Http\Controllers\API\Admin\UserController;
use App\Models\Ticket;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:sanctum', 'checkAdmin'])->group(function () {
  Route::apiResource('/car', CarController::class)->except(['update']);
  Route::post("/car/{id}/update", [CarController::class, "update"]);

  Route::get("/car/{id}/seat", [SeatController::class, "getByCar"]);
  Route::post("/car/{car}/seat", [SeatController::class, "store"]);
  Route::put("/car/seat/{seat}", [SeatController::class, "update"]);
  Route::delete("/car/{car}/seat/{seat}", [SeatController::class, "destroy"]);
  Route::post("/car/seat", [SeatController::class, "store"]);

  Route::get("comment", [CommentController::class, "index"]);
  Route::get("comment/{id}", [CommentController::class, "show"]);
  Route::put("comment/{comment}", [CommentController::class, "update"]);
  Route::post("/admin/comment", [CommentController::class, "store"]);
  Route::delete("comment/{comment}", [CommentController::class, "destroy"]);

  Route::apiResource('/news', NewsController::class)->only(['store', 'destroy']);
  Route::post("/news/update/{id}", [NewsController::class, "update"]);

  Route::apiResource('/trip', TripController::class)->only(['store', 'destroy', 'update', 'index']);
  Route::get('/statistical/trip', [TripController::class, "statisticalTrip"]);
  Route::post('/statisticalTripDetail/trip', [TripController::class, "statisticalTripDetail"]);

  Route::apiResource('/station', StationController::class)->except(['index']);
  Route::get("/station/{id}/point", [PointController::class, "getByStation"]);
  Route::post("/station/point", [PointController::class, "store"]);
  Route::put("/station/point/{point}", [PointController::class, "update"]);
  Route::delete("/station/point/{point}", [PointController::class, "destroy"]);

  Route::apiResource('/timepoint', timePointController::class);
  Route::apiResource('/ticket', TicketController::class);
  Route::apiResource('/job', JobController::class)->only(['store', 'destroy', 'update']);;

  Route::get("/statistics", [StatisticController::class, "index"]);

  Route::apiResource('/user', UserController::class);
  Route::apiResource('/driver', DriverController::class);
});
Route::middleware(['auth:sanctum', 'checkDriverOrAdmin'])->group(function () {
  Route::get("/driver/{id}/trip", [TripController::class, 'findByDriver']);
  Route::post("/trip/change-status", [TripController::class, "changeStatus"]);
});
