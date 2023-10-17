<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Library\HttpResponse;
use App\Http\Requests\ActionTripRequest;
use App\Http\Resources\TripCollection;
use App\Http\Resources\TripResource;
use App\Models\Trip;
use App\Repositories\Trip\TripRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TripController extends Controller
{
    private $tripRepository;

    public function __construct(TripRepository $tripRepository)
    {
        $this->tripRepository = $tripRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trips = $this->tripRepository->getAll();
        $tripCollection = new TripCollection($trips);
        return HttpResponse::respondWithSuccess($tripCollection);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ActionTripRequest $request)
    {
        $data = $request->validated();
        $this->tripRepository->create($data);
        return HttpResponse::respondWithSuccess([], "created successfully");
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $trip = $this->tripRepository->find($id);
        $tripResource = new TripResource($trip);
        return HttpResponse::respondWithSuccess($tripResource);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $trip = $this->tripRepository->find($id);
        $trip->update($request->all());
        return HttpResponse::respondWithSuccess([], "updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $status = $this->tripRepository->delete($id);
        if ($status) {
            return HttpResponse::respondWithSuccess([], "deleted successfully");
        }
    }
}
