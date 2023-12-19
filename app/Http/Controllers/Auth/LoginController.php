<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Library\HttpResponse;
use App\Http\Resources\User\UserResource;
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
            $token = $user->createToken("access_token", expiresAt: now()->addDay())->plainTextToken;

            if ($user->email_verified_at == null) {
                return HttpResponse::respondWithSuccess(['token' =>  $token], "email chưa được xác thực", 401);
            }
            return HttpResponse::respondWithSuccess([
                'token_type' => "Bearer",
                'access_token' => $token
            ], "Đăng nhập thành công");
        };
        return HttpResponse::respondError("tài khoản hoặc mật khẩu không hợp lệ");
    }

    public function getUser(Request $request)
    {
        return HttpResponse::respondWithSuccess(new UserResource(Auth::user()));
    }

    public function redirectToProvider($provider)
    {
        $validated = $this->validateProvider($provider);

        if (!is_null($validated)) {
            return $validated;
        }

        return HttpResponse::respondWithSuccess(['url' =>  Socialite::driver('google')->stateless()->redirect()->getTargetUrl()]);
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
            ['email' => $user->email],
            [
                'email_verified_at' => now(),
                'avatar' => $user->getAvatar(),
                "name" => $user->name,
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

        $token = $userCreated->createToken("access_token");
        return HttpResponse::respondWithSuccess([
            'token_type' => "Bearer",
            'access_token' => $token->plainTextToken
        ], "Đăng nhập thành công");
    }

    public function validateProvider($provider)
    {
        if (!in_array($provider, ['facebook', 'google'])) {
            return HttpResponse::respondError('chỉ đăng nhập bằng tài khoản google');
        }
    }
}
