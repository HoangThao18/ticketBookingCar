<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Library\HttpResponse;
use App\Http\Requests\SearchTripRequest;
use App\Http\Resources\PopularTripResource;
use App\Http\Resources\SearchTripsResource;
use App\Http\Resources\TripResource;
use App\Repositories\Station\StationRepositoryInterface;
use App\Repositories\Trip\TripRepositoryInterface;
use Illuminate\Http\Request;

class TripController extends Controller
{
    private $tripRepository;
    private $stationRepository;

    public function __construct(
        TripRepositoryInterface $tripRepository,
        StationRepositoryInterface $stationRepository,
    ) {
        $this->tripRepository = $tripRepository;
        $this->stationRepository = $stationRepository;
    }

    public function show($id)
    {
        $trip = $this->tripRepository->find($id);
        $tripResource = new TripResource($trip);
        return HttpResponse::respondWithSuccess($tripResource);
    }

    public function search(SearchTripRequest $request)
    {
        $startStation = $this->stationRepository->getByProvince($request->start_location);
        $endStation = $this->stationRepository->getByProvince($request->end_location);
        $trips = $this->tripRepository->getByRoute($startStation, $endStation, $request->date);
        $tripsWithAvailableSeats = [];
        foreach ($trips as $trip) {
            $totalSeats = $trip->car->number_seat;
            $soldTickets = $trip->tickets->where("status", "đã đặt")->count();
            $availableSeats = $totalSeats - $soldTickets;
            $trip->available_seats = $availableSeats;

            if ($availableSeats > $request->amount) {
                $trip->price = $trip->car->seats[0]->price;
                $trip->available_seats = $availableSeats;
                $tripsWithAvailableSeats[] = $trip;
            }
        }

        return HttpResponse::respondWithSuccess(SearchTripsResource::collection($tripsWithAvailableSeats));
    }

    public function getPopularTrips()
    {
        $trips = $this->tripRepository->getPopularTrips();
        return HttpResponse::respondWithSuccess(PopularTripResource::collection($trips));
    }
}
