<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Library\HttpResponse;
use App\Http\Requests\StoreNewsRequest;
use App\Models\News;
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
     * Store a newly created resource in storage.
     */
    public function store(StoreNewsRequest $request)
    {
        $news = $request->validated();
        if ($request->file('img')) {
            $file = $request->file('img');
            $fileName = $file->getClientOriginalName();
            $filePath = 'public/uploads/news';
            $path = $file->storeAs($filePath, $fileName);
            $news['img'] = $path;
        }
        $news = $this->newsRepository->create($news);
        return HttpResponse::respondWithSuccess([], "Tạo thành công");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $news = $request->all();
        if ($request->file('img')) {
            $file = $request->file('img');
            $fileName = $file->getClientOriginalName();
            $filePath = 'public/uploads/news';
            $path = $file->storeAs($filePath, $fileName);
            $news['img'] = $path;
        }
        $status =  $this->newsRepository->update($id, $news);
        if ($status) {
            return HttpResponse::respondWithSuccess([], "Cập nhật thành công");
        }
        return HttpResponse::respondError("Đã xảy ra lỗi");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $status = $this->newsRepository->delete($id);
        if ($status) {
            return HttpResponse::respondWithSuccess([], "Xóa thành công");
        }
        return HttpResponse::respondError("Đã xảy ra lỗi");
    }
}
