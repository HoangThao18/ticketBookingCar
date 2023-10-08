<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Library\HttpResponse;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{

    public function register(StoreUserRequest $request)
    {
        $userNew = $request->validated();
        User::create($userNew);
        return HttpResponse::respondWithSuccess(null, "User Created Successfully");
    }
}
