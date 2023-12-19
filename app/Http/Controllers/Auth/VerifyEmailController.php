<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Library\HttpResponse;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = User::find($request->route('id'));

        if ($user->hasVerifiedEmail()) {
            return HttpResponse::respondWithSuccess([], "email đã được xác minh");
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return HttpResponse::respondWithSuccess([], "xác minh minh thành công");
    }
}
