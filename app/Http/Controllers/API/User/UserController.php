<?php

namespace App\Http\Controllers\API\User;

use App\Http\Library\HttpResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => "required",
            'email' => ["required", "email", Rule::unique('users')->ignore(Auth::id()),],
            "address" => "required",
            "avatar" => "nullable",
            "phone_number" => ['required', 'min:10', Rule::unique('users')->ignore(Auth::id())]
        ]);

        if ($validator->fails()) {
            return HttpResponse::respondError($validator->errors(), 400);
        }

        $data = $validator->validated();

        if ($request->file('avatar')) {
            $file = $request->file('img');
            $fileName = $file->getClientOriginalName();
            $filePath = 'public/uploads/User';
            $path = $file->storeAs($filePath, $fileName);
            $data['avatar'] = $path;
        }

        $status = $this->userRepository->update(Auth::id(), $data);

        if (!$status) {
            return HttpResponse::respondError("something wrong");
        }
        return HttpResponse::respondWithSuccess("updated successfuly");
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => "required",
            'password' => "required|min:8|max:30",
            'confirm_password' => "required|same:password",
        ]);

        if ($validator->fails()) {
            return HttpResponse::respondError($validator->errors(), 400);
        }

        if (!Hash::check($request->old_password, Auth()->user()->password)) {
            return HttpResponse::respondError(["Old Password Doesn't match!"], 402);
            // return HttpResponse::respondError(['old_password' =>[ "Old Password Doesn't match!"]], 402);
        }

        $this->userRepository->changePassword(Auth()->user(), $request->password);
        return HttpResponse::respondWithSuccess("change password successfully");
    }
}
