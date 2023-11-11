<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Library\HttpResponse;
use App\Http\Requests\ActionTripRequest;
use App\Http\Resources\TripCollection;
use App\Http\Resources\TripResource;
use App\Models\Trip;
use App\Repositories\Seats\SeatsRepositoryInterface;
use App\Repositories\Trip\TripRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class TripController extends Controller
{
    private $tripRepository;
    private $seatsRepository;

    public function __construct(TripRepositoryInterface $tripRepository, SeatsRepositoryInterface $seatsRepository)
    {
        $this->tripRepository = $tripRepository;
        $this->seatsRepository = $seatsRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trips = $this->tripRepository->getTrips();
        foreach ($trips as $trip) {
            $totalSeats = $trip->car->number_seat;
            $soldTickets = $trip->tickets->where("status", "đã thanh toán")->count();
            $availableSeats = $totalSeats - $soldTickets;
            $trip->available_seats = $availableSeats;
        }
        $tripCollection = new TripCollection($trips);
        return HttpResponse::respondWithSuccess($tripCollection);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ActionTripRequest $request)
    {
        $data = $request->validated();
        $newTrip = $this->tripRepository->create($data);
        $seats = $this->seatsRepository->getByCar($data->car_id);
        return HttpResponse::respondWithSuccess([], "created successfully");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $trip = $this->tripRepository->update($id, $request->all());
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
