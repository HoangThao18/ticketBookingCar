<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTimePointRequest;
use App\Repositories\TimePoints\TimePointsRepositoryInterface;
use Illuminate\Http\Request;

class timePointController extends Controller
{
    private $timePointRepository;

    public function __construct(TimePointsRepositoryInterface  $timePointRepository)
    {
        $this->timePointRepository =  $timePointRepository;
    }

    public function store(StoreTimePointRequest $request)
    {
        $data = $request->validated();
        dd($data);
    }
}
