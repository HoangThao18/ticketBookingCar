<?php

use App\Http\Controllers\API\Admin\CarController;
use App\Http\Controllers\API\Admin\CommentController;
use App\Http\Controllers\API\Admin\NewsController;
use App\Http\Controllers\API\Admin\SeatController;
use App\Http\Controllers\API\Admin\StationController;
use App\Http\Controllers\API\Admin\timePointController;
use App\Http\Controllers\API\Admin\TripController;
use App\Http\Controllers\API\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'checkAdmin'])->group(function () {
  Route::apiResource('/car', CarController::class);
  Route::get("/car/{id}/seat", [SeatController::class, "getByCar"]);
  Route::apiResource('/seat', SeatController::class)->only('update', 'destroy');

  Route::apiResource('/comment', CommentController::class)->only(['index', 'update', 'destroy']);

  Route::apiResource('/news', NewsController::class)->only(['store', 'destroy', 'update']);

  Route::apiResource('/trip', TripController::class)->only(['store', 'destroy', 'update', 'index']);

  Route::apiResource('/station', StationController::class);

  Route::apiResource('/user', UserController::class);
  Route::get("/driver", [UserController::class, "getDriver"]);
  Route::post("timePoint", [timePointController::class, "store"]);
});
