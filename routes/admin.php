<?php

use App\Http\Controllers\API\Admin\TripController;
use App\Http\Controllers\API\Admin\UserControllser;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'checkAdmin'])->group(function () {
  Route::apiResource('/trip', UserControllser::class);
  Route::apiResource('/user', UserControllser::class);
});
