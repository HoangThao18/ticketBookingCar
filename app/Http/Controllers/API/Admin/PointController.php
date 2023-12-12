<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Library\HttpResponse;
use App\Http\Requests\StorePointRequest;
use App\Repositories\Points\PointsRepositoryInterface;
use Illuminate\Http\Request;

class PointController extends Controller
{
    private $pointRepository;

    public function __construct(PointsRepositoryInterface $pointRepository)
    {
        $this->pointRepository = $pointRepository;
    }


    public function getByStation($station_id)
    {
        $points = $this->pointRepository->getByStation($station_id);
        return HttpResponse::respondWithSuccess($points);
    }


    /**
     * store the specified resource in storage.
     */

    public function store(StorePointRequest $request)
    {
        $data = $request->validated();
        $this->pointRepository->create($data);
        return HttpResponse::respondWithSuccess([], "Tạo thành công");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $status =  $this->pointRepository->update($id, $request->all());
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
        $status = $this->pointRepository->delete($id);
        if ($status) {
            return HttpResponse::respondWithSuccess([], "Xóa thành công");
        }
        return HttpResponse::respondError("Đã xảy ra lỗi");
    }
}
