<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Library\HttpResponse;
use App\Http\Resources\Admin\Car\AdminCarResource;
use App\Http\Resources\Comment\AdminCommentResource;
use App\Repositories\Comment\CommentRepositoryInterface;
use Illuminate\Http\Request;

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


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $status =  $this->commentRepository->update($id, $request->all());
        if ($status) {
            return HttpResponse::respondWithSuccess([], "updated successfully");
        }
        return HttpResponse::respondError("Something wrong");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $status = $this->commentRepository->delete($id);
        if ($status) {
            return HttpResponse::respondWithSuccess([], "deleted successfully");
        }
        return HttpResponse::respondError("Something wrong");
    }
}
