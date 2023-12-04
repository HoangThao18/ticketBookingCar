<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Library\HttpResponse;
use App\Http\Requests\StoreTripRequest;
use App\Http\Resources\Admin\Trip\AdminTripResource;
use App\Models\Trip;
use App\Repositories\Seats\SeatsRepositoryInterface;
use App\Repositories\Ticket\TicketRepository;
use App\Repositories\Ticket\TicketRepositoryInterface;
use App\Repositories\TimePoints\TimePointsRepository;
use App\Repositories\Trip\TripRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class TripController extends Controller
{
    private $tripRepository;
    private $timePointsRepository;

    public function __construct(
        TripRepositoryInterface $tripRepository,
        TimePointsRepository $timePointsRepository
    ) {
        $this->tripRepository = $tripRepository;
        $this->timePointsRepository = $timePointsRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trips = $this->tripRepository->getTrips();
        return HttpResponse::respondWithSuccess(AdminTripResource::collection($trips));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTripRequest $request)
    {

        $tripExsits = $this->tripRepository->getByCarAndDepart($request->car_id, $request->departure_time);
        if ($tripExsits) {
            return HttpResponse::respondError("chuyến xe đã tồn tại");
        }
        $data = $request->except('pickups', 'dropoff');
        $newTrip = $this->tripRepository->create($data);
        $data_timePoints_pickups = [];
        $data_timePoints_dropoff = [];

        if ($request->pickups) {
            foreach ($request->pickups as  $value) {
                $data_timePoints_pickups[] =  ["type" => "pickup", "time" => $value['time'], "point_id" => $value['pointId'], "trip_id" => $newTrip->id];
            }
        }

        if ($request->dropoff) {
            foreach ($request->dropoff as  $value) {
                $data_timePoints_dropoff[] =  ["type" => "dropoff", "time" => $value['time'], "point_id" => $value['pointId'], "trip_id" => $newTrip->id];
            }
        }
        $newTrip->time_points()->createMany($data_timePoints_pickups);
        $newTrip->time_points()->createMany($data_timePoints_dropoff);
        return HttpResponse::respondWithSuccess([], "created successfully");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $status =  $this->tripRepository->update($id, $request->except('pickups', 'dropoff'));
        if (!$status) {
            return HttpResponse::respondError("Something wrong");
        }

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
