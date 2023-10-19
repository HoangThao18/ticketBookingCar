<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Library\HttpResponse;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return HttpResponse::respondWithSuccess(null, "logout successfully");
    }
}
