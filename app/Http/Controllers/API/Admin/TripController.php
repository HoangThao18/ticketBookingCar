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
use App\Repositories\Bill\BillRepository;
use App\Repositories\Bill\BillRepositoryInterface;
use App\Repositories\TimePoints\TimePointsRepository;
use App\Repositories\Trip\TripRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreStationRequest;
use App\Http\Resources\Admin\ticket\AdminDetailTicketResource;
use App\Repositories\Station\StationRepositoryInterface;

class TripController extends Controller
{
    private $tripRepository;
    private $timePointsRepository;
    private $stationRepository;
    private $billRepository;
    private $ticketRepository;

    public function __construct(
        TripRepositoryInterface $tripRepository,
        TimePointsRepository $timePointsRepository,
        StationRepositoryInterface $stationRepository,
        BillRepositoryInterface $billRepository,
        TicketRepositoryInterface $ticketRepository
    ) {
        $this->tripRepository = $tripRepository;
        $this->timePointsRepository = $timePointsRepository;
        $this->stationRepository = $stationRepository;
        $this->billRepository = $billRepository;
        $this->ticketRepository = $ticketRepository;
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

    public function findByDriver($id)
    {
        $trips = $this->tripRepository->findTripByDriver($id);
        return HttpResponse::respondWithSuccess(AdminTripResource::collection($trips));
    }

    public function statisticalTrip()
    {
        $trips = $this->tripRepository->getTrips();
        $trips = AdminTripResource::collection($trips);
        $station = $this->stationRepository;
        $stations = $station->getAll();
        $provinces = [];
        foreach ($stations as $value) {
            if (!in_array($value->province, $provinces)) {
                $stationID = $station->getByProvince($value->province);
                $provinces[$value->province] = $stationID;
            }
        }
        foreach ($provinces as $key => $province) {
            $count = 0;
            foreach ($province as $id) {
                foreach ($trips as $trip) {
                    if ($trip->start_station == $id) {
                        $count++;
                    }
                }
            }
            $provinces[$key] = $count;
            // $result[$province] = $count;
        }
        arsort($provinces);
        $result = array_slice($provinces, 0, 5);
        return HttpResponse::respondWithSuccess($result, "5 Tỉnh có nhiều chuyến xe nhất");
    }

    public function statisticalTripDetail(Request $request)
    {
        $trips = $this->tripRepository;
        $trips = $trips->getByStatus($request->status,$request->day);
        if($trips == null){
            return HttpResponse::respondError("Không có chuyến xe nào tồn tại");
        }
        $statisticalTripDetail = [];
        foreach ($trips as $trip) {
            $statisticalTripDetail[] = new AdminTripResource($trip);
        }
        foreach ($statisticalTripDetail as $key => $value) {
            $totalMoney = 0;
            $totalSeat = $value->car->number_seat;
            $ticket = $this->ticketRepository->getByTrip($value->id);
            $totalSeatSold = count($ticket);
            $tickets["trip_id"][$value->id] = $ticket;
            foreach ($ticket as $item) {
                $totalMoney += $item->price;
            }
            $statisticalTripDetail[$key] = [
                "trip" => $value,
                "total_seat" => $totalSeat,
                "total_seat_sold" => $totalSeatSold,
                "total_money" => $totalMoney,
            ];
        }
        // return HttpResponse::respondWithSuccess(new AdminDetailTicketResource($ticket));
        return HttpResponse::respondWithSuccess($statisticalTripDetail, "");
    }
}
