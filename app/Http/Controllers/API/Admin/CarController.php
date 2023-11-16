<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Library\HttpResponse;
use App\Models\Car;
use App\Repositories\Car\CarRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCarRequest;
use App\Http\Resources\Admin\Car\AdminCarResource;

class CarController extends Controller
{
    private $carRepository;

    public function __construct(CarRepository $carRepository)
    {
        $this->carRepository = $carRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cars = $this->carRepository->getAll();
        return HttpResponse::respondWithSuccess(AdminCarResource::collection($cars)->response()->getData(true));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCarRequest $request)
    {
        $car = $request->validated();
        if ($request->file('img')) {
            $file = $request->file('img');
            $fileName = $file->getClientOriginalName();
            $filePath = 'public/uploads/car';
            $path = $file->storeAs($filePath, $fileName);
            $car['img'] = $path;
        }
        $this->carRepository->create($car);
        return HttpResponse::respondWithSuccess([], 'created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $car = $this->carRepository->find($id);
        return HttpResponse::respondWithSuccess($car);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $status =  $this->carRepository->update($id, $request->all());
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
