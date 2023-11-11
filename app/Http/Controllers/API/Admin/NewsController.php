<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Library\HttpResponse;
use App\Http\Requests\StoreNewsRequest;
use App\Repositories\News\NewsRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Exists;

class NewsController extends Controller
{
    private $newsRepository;
    public function __construct(NewsRepositoryInterface $newsRepository)
    {
        $this->newsRepository = $newsRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNewsRequest $request)
    {
        $news = $this->newsRepository->create($request->except(['imgs']));
        if ($request->file('img')) {
            $file = $request->file('img');
            $fileName = $file->getClientOriginalName();
            $filePath = 'public/uploads/news';
            $path = $file->storeAs($filePath, $fileName);
            $news->saveImage($path);
        }
        return HttpResponse::respondWithSuccess([], "created successfully");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
