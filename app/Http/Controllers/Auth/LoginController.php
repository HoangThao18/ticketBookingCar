<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Library\HttpResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validator  = Validator::make($request->all(), [
            'username' => 'required|max:30',
            "password" => 'required'
        ]);

        if ($validator->fails()) {
            return HttpResponse::respondError($validator->errors());
        }

        if (Auth::attempt(['username' => $request->username, "password" => $request->password])) {
            $user = User::where('username', $request->username)->first();
            $token = $user->createToken("access_token");

            return HttpResponse::respondWithSuccess([
                'token_type' => "Bearer",
                'access_token' => $token->plainTextToken
            ], "User Logged In Successfully");
        };
        return HttpResponse::respondError("Username or password incorrect");
    }

    public function getUser(Request $request)
    {
        return $request->user();
    }
}
