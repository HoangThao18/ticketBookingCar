<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Library\HttpResponse;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\Comment\CommentResource;
use App\Models\Comment;
use App\Repositories\Comments\CommentsRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CommentController extends Controller
{
    private $commentRepository;

    public function __construct(CommentsRepositoryInterface $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }
    public function show($id)

    {
        $comments = Comment::where('car_id', $id)->get();
        return HttpResponse::respondWithSuccess(CommentResource::collection($comments));
    }

    public function store(StoreCommentRequest $request)
    {
        $this->commentRepository->create(array_merge($request->validated(), ['user_id' => Auth::id()]));
        return HttpResponse::respondWithSuccess([], "Thành công");
    }
}
