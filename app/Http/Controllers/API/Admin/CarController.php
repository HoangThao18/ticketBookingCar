<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Library\HttpResponse;
use App\Models\Car;
use App\Repositories\Car\CarRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCarRequest;
use App\Http\Resources\Admin\Car\AdminCarResource;
use App\Repositories\Car\CarRepositoryInterface;
use App\Repositories\Seats\SeatsRepository;
use App\Repositories\Seats\SeatsRepositoryInterface;
use App\Repositories\Ticket\TicketRepositoryInterface;

class CarController extends Controller
{
    private $carRepository;
    private $seatsRepository;

    public function __construct(
        CarRepositoryInterface $carRepository,
        SeatsRepositoryInterface $seatsRepository
    ) {
        $this->carRepository = $carRepository;
        $this->seatsRepository = $seatsRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cars = $this->carRepository->getAll();
        return HttpResponse::respondWithSuccess(AdminCarResource::collection($cars));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCarRequest $request)
    {
        $car = $request->validated();
        $data_seats = [];
        if ($request->file('img')) {
            $file = $request->file('img');
            $fileName = $file->getClientOriginalName();
            $filePath = 'public/uploads/car';
            $path = $file->storeAs($filePath, $fileName);
            $car['img'] = $path;
        }
        $car = $this->carRepository->create($car);

        foreach ($request->seats as $seat) {
            $data_seats[] = ["car_id" => $car->id, "position" => $seat['position'], "type" => $seat['type'], "price" => $seat['price']];
        }
        $car->seats()->createMany($data_seats);
        return HttpResponse::respondWithSuccess([], 'created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $car = $this->carRepository->find($id);
        return HttpResponse::respondWithSuccess(new AdminCarResource($car));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $updateData = $request->all();
        if ($request->file('img')) {
            $file = $request->file('img');
            $fileName = $file->getClientOriginalName();
            $filePath = 'public/uploads/car';
            $path = $file->storeAs($filePath, $fileName);
            $updateData['img'] = $path;
        }
        $status =  $this->carRepository->update($id, $updateData);

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
        $status = $this->carRepository->delete($id);
        if ($status) {
            return HttpResponse::respondWithSuccess([], "deleted successfully");
        }
        return HttpResponse::respondError("Something wrong");
    }
}
