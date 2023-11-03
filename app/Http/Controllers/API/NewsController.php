<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Library\HttpResponse;
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
        return HttpResponse::respondWithSuccess($news);
    }

    public function show($id)
    {
        $news = $this->newsRepository->find($id);
        return HttpResponse::respondWithSuccess($news);
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
