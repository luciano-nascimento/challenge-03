<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use App\Services\JobService;
use Illuminate\Support\Facades\Log;

class JobController extends Controller
{

    protected $service;

    public function __construct(JobService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Job $job)
    {
        Log::info("Listing all jobs.");
        return $this->service->findAll();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:100',
            'description' => 'required',
        ]);

        Log::info("Starting processing to store job ${request['title']}.");
        return $this->service->store($request);
    }

    public function show($id)
    {
        return Job::findOrFail($id);
    }

}
