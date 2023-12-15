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
use Illuminate\Support\Facades\Validator;

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
            return HttpResponse::respondError("Đã xảy ra lỗi");
        }

        return HttpResponse::respondWithSuccess([], "Cập nhật thành công");
    }

    public function getByDriver($driverId)
    {
        $trips = $this->tripRepository($driverId);
        return HttpResponse::respondWithSuccess(AdminTripResource::collection($trips));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $status = $this->tripRepository->delete($id);
        if ($status) {
            return HttpResponse::respondWithSuccess([], "Xóa thành công");
        }
    }

    public function changeStatus(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required|exists:trips',
                'status' => 'required|string',
            ],
            [

                'id.required' => 'Bạn chưa nhập Id', // custom message
                'status.required' => 'bạn chưa nhập status', // custom message
                'id.exists' => 'Chuyến xe không tồn tại' // custom message

            ]
        );

        if ($validator->fails()) {
            return HttpResponse::respondError($validator->errors());
        }

        if (!$this->tripRepository->changeStatus($request->id, $request->status)) {
            return HttpResponse::respondWithSuccess("cập nhật thất bại");
        }
        return HttpResponse::respondWithSuccess("cập nhật trạng thái thành công");
    }

    public function findByDriver($id){
        $trips = $this->tripRepository->findTripByDriver($id);
        return HttpResponse::respondWithSuccess(AdminTripResource::collection($trips));
    }
}
