<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Library\HttpResponse;
use App\Http\Resources\News\NewsResource;
use App\Repositories\News\NewsRepositoryInterface;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    private $newsRepository;

    public function __construct(NewsRepositoryInterface $newsRepository)
    {
        $this->newsRepository = $newsRepository;
    }

    public function index()
    {
        $news = $this->newsRepository->getAll();
        return HttpResponse::respondWithSuccess(NewsResource::collection($news)->response()->getData(true));
    }

    public function show($id)
    {
        $new = $this->newsRepository->find($id);
        $this->newsRepository->increaseView($new);
        return HttpResponse::respondWithSuccess(new NewsResource($new));
    }

    public function getPopularNews()
    {
        return HttpResponse::respondWithSuccess($this->newsRepository->getPopularNews());
    }

    public function getLatestNews()
    {
        return HttpResponse::respondWithSuccess($this->newsRepository->getLatestNews());
    }
}
