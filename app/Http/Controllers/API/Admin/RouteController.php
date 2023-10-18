<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Library\HttpResponse;
use App\Http\Requests\StoreRouteRequest;
use App\Repositories\Route\RouteRepositoryInterface;
use Illuminate\Http\Request;

class RouteController extends Controller
{

    private $routeRepository;

    public function __construct(RouteRepositoryInterface $routeRepository)
    {
        $this->routeRepository = $routeRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $routes = $this->routeRepository->getAll();
        return HttpResponse::respondWithSuccess($routes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRouteRequest $request)
    {
        $data = $request->validated();
        $this->routeRepository->create($data);
        return HttpResponse::respondWithSuccess([], "created successfully");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $route = $this->routeRepository->find($id);
        return HttpResponse::respondWithSuccess($route);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $status =  $this->routeRepository->update($id, $request->all());
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
        $status = $this->routeRepository->delete($id);
        if ($status) {
            return HttpResponse::respondWithSuccess([], "deleted successfully");
        }
        return HttpResponse::respondError("Something wrong");
    }
}
