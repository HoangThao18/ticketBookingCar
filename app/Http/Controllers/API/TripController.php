<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Library\HttpResponse;
use App\Http\Resources\TripResource;
use App\Repositories\Trip\TripRepository;
use Illuminate\Http\Request;

class TripController extends Controller
{
    private $tripRepository;

    public function __construct(TripRepository $tripRepository)
    {
        $this->tripRepository = $tripRepository;
    }

    public function show($id)
    {
        $trip = $this->tripRepository->find($id);
        $tripResource = new TripResource($trip);
        return HttpResponse::respondWithSuccess($tripResource);
    }
}
