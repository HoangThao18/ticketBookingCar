<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Library\HttpResponse;
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
     * Display a listing of the resource.
     */
    public function index()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $status =  $this->seatRepository->update($id, $request->all());
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
        $status = $this->seatRepository->delete($id);
        if ($status) {
            return HttpResponse::respondWithSuccess([], "deleted successfully");
        }
        return HttpResponse::respondError("Something wrong");
    }

    public function getByCar($id)
    {
        $seats = $this->seatRepository->getByCar($id);
        return HttpResponse::respondWithSuccess($seats);
    }
}
