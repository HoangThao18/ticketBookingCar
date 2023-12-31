<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Library\HttpResponse;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;


class RegisterController extends Controller
{

    public function register(StoreUserRequest $request)
    {
        $userNew = $request->validated();
        $user = User::create($userNew);
        return HttpResponse::respondWithSuccess(null, "Đăng kí thành công");
    }
}
