<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Library\HttpResponse;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

use function Laravel\Prompts\password;

class ResetPasswordController extends Controller
{
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => "required|email"
        ]);

        if ($validator->fails()) {
            return  HttpResponse::respondError($validator->errors()->first(), 400);
        }

        try {
            $status = Password::sendResetLink($request->only('email'));
            if ($status ==  Password::RESET_LINK_SENT) {
                return HttpResponse::respondWithSuccess(__($status));
            } else {
                return HttpResponse::respondError(__($status));
            }
        } catch (\Exception $e) {
            return HttpResponse::respondError($e->getMessage());
        }
    }

    public function reset(ResetPasswordRequest $request)
    {

        $status = Password::reset(
            $request->only('email', 'password', "confirm_password", "token"),
            function ($user, $password) {
                $user->password = $password;
                $user->save();
            }
        );
        if ($status ==  Password::PASSWORD_RESET) {
            return HttpResponse::respondWithSuccess(null, __($status));
        }

        return HttpResponse::respondError(__($status));
    }
}
