<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Repositories\JobRepository;
use Illuminate\Foundation\Validation\ValidatesRequests;

class JobService
{

    use ValidatesRequests;

    public function __construct(JobRepository $repository, 
        PublisherService $publisher)
    {
        $this->repository = $repository;
        $this->publisher = $publisher;
    }

    public function store(Request $request) : Job
    {
        //only regular user can store that
        //maybe it could be filtered using token
        $job = $this->repository->store($request->all());
        $this->publisher->publishMessage($job->title);
        return $job;
        
    }

    public function findAll()
    {
        //only regular user can see that
        //maybe it could be filtered using token
        return $this->repository->findAll();
    }

    
}