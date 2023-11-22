<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Library\HttpResponse;
use App\Http\Requests\StoreSeatRequest;
use App\Repositories\Seats\SeatsRepositoryInterface;
use Illuminate\Http\Request;

class SeatController extends Controller
{

    private $seatRepository;

    public function __construct(SeatsRepositoryInterface $seatRepository)
    {
        $this->seatRepository = $seatRepository;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSeatRequest $request)
    {
        $data = $request->validated();
        $this->seatRepository->create($data);
        return HttpResponse::respondWithSuccess([], "created successfully");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $seat)
    {
        $status =  $this->seatRepository->update($seat, $request->all());
        if ($status) {
            return HttpResponse::respondWithSuccess([], "updated successfully");
        }
        return HttpResponse::respondError("Something wrong");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $seat)
    {
        $status = $this->seatRepository->delete($seat);
        if ($status) {
            return HttpResponse::respondWithSuccess([], "deleted successfully");
        }
        return HttpResponse::respondError("Something wrong");
    }

    public function getByCar($seat)
    {
        $seats = $this->seatRepository->getByCar($seat);
        return HttpResponse::respondWithSuccess($seats);
    }
}
