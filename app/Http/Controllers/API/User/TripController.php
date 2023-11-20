<?php

namespace App\Http\Controllers\API\User;


use App\Http\Controllers\Controller;
use App\Http\Library\HttpResponse;
use App\Http\Requests\SearchTripRequest;
use App\Http\Resources\Trip\SearchTripsResource;
use App\Http\Resources\Trip\TripResource;
use App\Repositories\Seats\SeatsRepositoryInterface;
use App\Repositories\Station\StationRepositoryInterface;
use App\Repositories\Ticket\TicketRepositoryInterface;
use App\Repositories\Trip\TripRepositoryInterface;
use Illuminate\Http\Request;

class TripController extends Controller
{
    private $tripRepository;
    private $stationRepository;
    private $seatsRepository;
    private $TicketsRepository;


    public function __construct(
        TripRepositoryInterface $tripRepository,
        StationRepositoryInterface $stationRepository,
        SeatsRepositoryInterface $seatsRepository,
        TicketRepositoryInterface $TicketsRepository,

    ) {
        $this->tripRepository = $tripRepository;
        $this->stationRepository = $stationRepository;
        $this->seatsRepository = $seatsRepository;
        $this->TicketsRepository = $TicketsRepository;
    }

    public function show($id)
    {
        $trip = $this->tripRepository->find($id);

        $seatsCar = $this->seatsRepository->getByCar($trip->car_id);
        $tickets = $this->TicketsRepository->getByTrip($trip->id);

        foreach ($seatsCar as $seat) {
            $isSeatBooked = $tickets->contains('seat_id', $seat->id);
            $seat->status = $isSeatBooked ? $tickets->where('seat_id', $seat->id)->first()->status : 'Available';
            $tripSeats[] = $seat;
        };

        $trip->setAttribute('seats', $tripSeats);
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
            $soldTickets = $this->TicketsRepository->CountSoldTickets($trip->id);
            $availableSeats = $totalSeats - $soldTickets;
            $trip->available_seats = $availableSeats;

            if ($availableSeats > $request->amount) {
                $trip->price = min(array_column($trip->car->seats->toArray(), 'price'));;
                $trip->available_seats = $availableSeats;
                $tripsWithAvailableSeats[] = $trip;
            }
        }

        return HttpResponse::respondWithSuccess(SearchTripsResource::collection($tripsWithAvailableSeats));
    }

    public function getPopularTrips()
    {
        $trips = $this->tripRepository->getPopularTrips();
        return HttpResponse::respondWithSuccess($trips);
    }
}
