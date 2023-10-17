<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Library\HttpResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validator  = Validator::make($request->all(), [
            'email' => 'required|max:30|email',
            "password" => 'required'
        ]);

        if ($validator->fails()) {
            return HttpResponse::respondError($validator->errors());
        }

        if (Auth::attempt(['email' => $request->email, "password" => $request->password])) {
            $user = User::where('email', $request->email)->first();
            $user->last_login_date = now();
            $user->save();
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
        return HttpResponse::respondWithSuccess($request->user());
    }

    public function redirectToProvider($provider)
    {
        $validated = $this->validateProvider($provider);

        if (!is_null($validated)) {
            return $validated;
        }
        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $validated = $this->validateProvider($provider);
        if (!is_null($validated)) {
            return $validated;
        }

        $user =  Socialite::driver($provider)->stateless()->user();

        if (!$user) {
            HttpResponse::respondNotFound(null);
        }

        $userCreated = User::firstOrCreate(
            [
                'email' => $user->getEmail(),
            ],
            [
                'email_verified_at' => now(),
                'name' => $user->getName(),
            ]
        );

        $userCreated->providers()->updateOrCreate(
            [
                'provider' => $provider,
                'provider_id' => $user->getId(),
            ],
            [
                'avatar' => $user->getAvatar()
            ]
        );

        $token = $userCreated->createToken("access_token", expiresAt: now()->addDay(2));
        return HttpResponse::respondWithSuccess([
            'token_type' => "Bearer",
            'access_token' => $token->plainTextToken
        ], "User Logged In Successfully");
    }

    public function validateProvider($provider)
    {
        if (!in_array($provider, ['facebook', 'google'])) {
            return HttpResponse::respondError('Please login using facebook, google');
        }
    }
}
