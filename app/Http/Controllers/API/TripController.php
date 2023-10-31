<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Library\HttpResponse;
use App\Http\Resources\PopularTripResource;
use App\Http\Resources\SearchTripsResource;
use App\Http\Resources\TripResource;
// use App\Repositories\CarImgs\CarImgsRepositoryInterface;
use App\Repositories\Route\RouteRepositoryInterface;
use App\Repositories\Trip\TripRepositoryInterface;
use Illuminate\Http\Request;

class TripController extends Controller
{
    private $tripRepository;
    private $routeRepository;
    private $carImgsRepository;

    public function __construct(
        TripRepositoryInterface $tripRepository,
        RouteRepositoryInterface $routeRepository,
        // CarImgsRepositoryInterface  $carImgsRepository
    ) {
        $this->tripRepository = $tripRepository;
        $this->routeRepository = $routeRepository;
        // $this->carImgsRepository = $carImgsRepository;
    }

    public function show($id)
    {
        $trip = $this->tripRepository->find($id);
        $totalSeats = $trip->car->number_seat;
        $soldTickets = $trip->tickets->where("status", "đã thanh toán")->count();
        $tripResource = new TripResource($trip);
        return HttpResponse::respondWithSuccess($tripResource);
    }

    public function search(Request $request)
    {
        $route = $this->routeRepository->searchByLocation($request->start_location, $request->end_location);
        $trips = $this->tripRepository->findByRoute($route->id, $request->time);

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

    public function getPopularTrips(Request $request)
    {
        $trips = $this->tripRepository->getPopularTrips($request->location);
        return HttpResponse::respondWithSuccess($trips);
    }
}
