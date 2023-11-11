<?php

namespace App\Http\Controllers\API;

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
    public function show(string $id)
    {
        //
    }
}
