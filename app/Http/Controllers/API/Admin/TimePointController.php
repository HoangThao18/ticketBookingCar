<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Library\HttpResponse;
use App\Http\Requests\StoreTimePointRequest;
use App\Http\Resources\Admin\Timepoint\TimePointResource;
use App\Repositories\TimePoints\TimePointsRepositoryInterface;
use Illuminate\Http\Request;

class TimePointController extends Controller
{
    private $timePointRepository;

    public function __construct(TimePointsRepositoryInterface $timePointRepository)
    {
        $this->timePointRepository = $timePointRepository;
    }

    public function show($id)
    {
        $data = $this->timePointRepository->find($id);
        return HttpResponse::respondWithSuccess(new TimePointResource($data));
    }

    public function store(StoreTimePointRequest $request)
    {
        $this->timePointRepository->create($request->validated());
        return HttpResponse::respondWithSuccess([], "Tạo thành công");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $status =  $this->timePointRepository->update($id, $request->all());
        if ($status) {
            return HttpResponse::respondWithSuccess([], "Cập nhật thành công");
        }
        return HttpResponse::respondError("Đã xảy ra lỗi");
    }


    public function destroy(string $id)
    {
        $status = $this->timePointRepository->delete($id);
        if ($status) {
            return HttpResponse::respondWithSuccess([], "Xóa thành công");
        }
        return HttpResponse::respondError("Đã xảy ra lỗi");
    }
}
