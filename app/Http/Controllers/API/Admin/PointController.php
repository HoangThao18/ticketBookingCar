<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Library\HttpResponse;
use App\Repositories\Points\PointsRepositoryInterface;
use Illuminate\Http\Request;

class PointController extends Controller
{
    private $pointRepository;

    public function __construct(PointsRepositoryInterface $pointRepository)
    {
        $this->pointRepository = $pointRepository;
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $status =  $this->pointRepository->update($id, $request->all());
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
        $status = $this->pointRepository->delete($id);
        if ($status) {
            return HttpResponse::respondWithSuccess([], "deleted successfully");
        }
        return HttpResponse::respondError("Something wrong");
    }
}
