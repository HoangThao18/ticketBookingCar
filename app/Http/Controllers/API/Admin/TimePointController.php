<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Library\HttpResponse;
use App\Http\Requests\StoreTimePointRequest;
use App\Repositories\TimePoints\TimePointsRepositoryInterface;
use Illuminate\Http\Request;

class TimePointController extends Controller
{
    private $timePointRepository;

    public function __construct(TimePointsRepositoryInterface $timePointRepository)
    {
        $this->timePointRepository = $timePointRepository;
    }

    public function store(StoreTimePointRequest $request)
    {
        $this->timePointRepository->create($request->validated());
        return HttpResponse::respondWithSuccess([], "created successfully");
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
