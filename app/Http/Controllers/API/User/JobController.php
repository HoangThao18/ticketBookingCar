<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Http\Library\HttpResponse;
use App\Repositories\Job\JobRepositoryInterface;
use Illuminate\Http\Request;

class JobController extends Controller
{
    private $jobRepository;

    public function __construct(JobRepositoryInterface $jobRepository)
    {
        $this->jobRepository = $jobRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return HttpResponse::respondWithSuccess($this->jobRepository->getAll());
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $job = $this->jobRepository->find($id);
        return HttpResponse::respondWithSuccess($job);
    }
}
