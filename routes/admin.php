<?php

use App\Http\Controllers\API\Admin\NewsController;
use App\Http\Controllers\API\Admin\RouteController;
use App\Http\Controllers\API\Admin\StationController;
use App\Http\Controllers\API\Admin\TripController;
use App\Http\Controllers\API\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'checkAdmin'])->group(function () {
  Route::post("/news", [NewsController::class, 'store']);
  Route::post('/trip', [TripController::class, "store"]);
  Route::get('/trip', [TripController::class, "index"]);
  Route::put('/trip', [TripController::class, "update"]);
  Route::delete('/trip/{trip}', [TripController::class, "destroy"]);
  Route::apiResource('/user', UserController::class);
  Route::apiResource('/route', RouteController::class);
});
