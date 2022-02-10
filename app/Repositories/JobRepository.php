<?php

namespace App\Repositories;

use App\Models\Job;

class JobRepository
{

    public function store(Array $data)
    {
        return Job::create($data);
    }

    public function findAll()
    {
        return Job::all();
    }

}