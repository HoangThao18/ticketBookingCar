<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Library\HttpResponse;
use App\Http\Requests\AdminStoreComment;
use App\Http\Resources\Admin\Car\AdminCarResource;
use App\Http\Resources\Comment\AdminCommentResource;
use App\Repositories\Comment\CommentRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    private $commentRepository;

    public function __construct(CommentRepositoryInterface $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comments = $this->commentRepository->getAll();
        return HttpResponse::respondWithSuccess(AdminCommentResource::collection($comments));
    }


    public function show($id)
    {
        $comment = $this->commentRepository->find($id);
        return HttpResponse::respondWithSuccess(new AdminCommentResource($comment));
    }

    public function store(AdminStoreComment $request)
    {
        $this->commentRepository->create(array_merge($request->validated(), ['user_id' => Auth::id()]));
        return HttpResponse::respondWithSuccess([], "creared successfully");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $comment)
    {
        $status =  $this->commentRepository->update($comment, $request->all());
        if ($status) {
            return HttpResponse::respondWithSuccess([], "updated successfully");
        }
        return HttpResponse::respondError("Something wrong");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $comment)
    {
        $status = $this->commentRepository->delete($comment);
        if ($status) {
            return HttpResponse::respondWithSuccess([], "deleted successfully");
        }
        return HttpResponse::respondError("Something wrong");
    }
}
