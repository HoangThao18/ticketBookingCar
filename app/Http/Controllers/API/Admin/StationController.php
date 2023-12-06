<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Library\HttpResponse;
use App\Http\Requests\StoreStationRequest;
use App\Repositories\Station\StationRepositoryInterface;
use Illuminate\Http\Request;

class StationController extends Controller
{
    private $stationRepository;

    public function __construct(StationRepositoryInterface $stationRepository)
    {
        $this->stationRepository = $stationRepository;
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStationRequest $request)
    {
        $data = $request->validated();
        $station =  $this->stationRepository->create($data);
        $data_points = [];
        if ($request->points) {
            foreach ($request->points as  $value) {
                $data_points[] =  ["name" => $value['name'], "address" => $value['address'], "station_id" => $station->id];
            }
        };
        $station->points()->createMany($data_points);
        return HttpResponse::respondWithSuccess([], "created successfully");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $station = $this->stationRepository->find($id);
        return HttpResponse::respondWithSuccess($station);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $status =  $this->stationRepository->update($id, $request->all());
        if ($status) {
            return HttpResponse::respondWithSuccess([], "updated successfully");
        }
        return HttpResponse::respondError("Something wrong");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $status = $this->stationRepository->delete($id);
        if ($status) {
            return HttpResponse::respondWithSuccess([], "deleted successfully");
        }
        return HttpResponse::respondError("Something wrong");
    }
}
