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
        return HttpResponse::respondWithSuccess([], "created successfully");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $status =  $this->timePointRepository->update($id, $request->all());
        if ($status) {
            return HttpResponse::respondWithSuccess([], "updated successfully");
        }
        return HttpResponse::respondError("Something wrong");
    }


    public function destroy(string $id)
    {
        $status = $this->timePointRepository->delete($id);
        if ($status) {
            return HttpResponse::respondWithSuccess([], "deleted successfully");
        }
        return HttpResponse::respondError("Something wrong");
    }
}
