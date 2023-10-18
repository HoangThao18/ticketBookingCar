<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Library\HttpResponse;
use App\Http\Resources\UserResource;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = $this->userRepository->getAll();
        $usersCollection = UserResource::collection($users)->response()->getData(true);
        return HttpResponse::respondWithSuccess($usersCollection);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = $this->userRepository->find($id);
        return HttpResponse::respondWithSuccess(new UserResource($user));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->userRepository->update($id, $request->all());
        return HttpResponse::respondWithSuccess([], "updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $status = $this->userRepository->delete($id);
        if ($status) {
            return HttpResponse::respondWithSuccess([], "deleted successfully");
        }
    }
}
