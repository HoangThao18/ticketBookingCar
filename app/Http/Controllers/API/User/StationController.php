<?php

namespace App\Http\Controllers\API\User;


use App\Http\Controllers\Controller;
use App\Http\Library\HttpResponse;
use App\Http\Resources\provinceResource;
use App\Repositories\Station\StationRepositoryInterface;
use Illuminate\Http\Request;

class StationController extends Controller
{
    private $stationRepository;

    public function __construct(StationRepositoryInterface $stationRepository)
    {
        $this->stationRepository = $stationRepository;
    }

    public function getProvince()
    {
        $provinces = $this->stationRepository->getProvince();
        return HttpResponse::respondWithSuccess($provinces);
    }
}
