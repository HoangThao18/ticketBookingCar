<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Library\HttpResponse;
use App\Http\Requests\StoreJobRequest;
use App\Repositories\Job\JobRepositoryInterface;
use Illuminate\Http\Request;

class JobController extends Controller
{
    private $jobRepository;

    public function __construct(JobRepositoryInterface $jobRepository)
    {
        $this->jobRepository = $jobRepository;
    }

    public function store(StoreJobRequest $request)
    {
        $this->jobRepository->create($request->validated());
        return HttpResponse::respondWithSuccess([], "created successfully");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->jobRepository->update($id, $request->all());
        return HttpResponse::respondWithSuccess([], "updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $status = $this->jobRepository->delete($id);
        if ($status) {
            return HttpResponse::respondWithSuccess([], "deleted successfully");
        }
    }
}
