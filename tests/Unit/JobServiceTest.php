<?php

namespace Tests\Unit;

use App\Models\Job;
use Mockery;
use Mockery as m;
use App\Services\JobService;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use Mockery\Adapter\Phpunit\MockeryTestCase;

class JobServiceTest extends MockeryTestCase
{

    protected function setUp(): void
    {
        $this->jobRepository = m::mock('App\Repositories\JobRepository');
        $this->publisher = m::mock('App\Services\PublisherService');
        $this->jobService = new JobService($this->jobRepository, $this->publisher);
    }

    public function testShouldStorejob()
    {
        $data = ['title' => 'title', 'description' => 'desc'];

        $job = Job::factory()->make();

        $this->jobRepository->shouldReceive('store')
            ->andReturns($job)
            ->once();
        
        $this->publisher->shouldReceive('publishMessage')
            ->withArgs([$data['title']])
            ->andReturns(false);

        $response = $this->jobService->store(Request::create('', 'POST', $data));
        
        $this->assertSame($job, $response);
    }

    public function testShouldReturnAllJobs()
    {
        $job = Job::factory()->make();

        $this->jobRepository->shouldReceive('findAll')
            ->andReturns(collect([$job, $job]))
            ->once();

        $response = $this->jobService->findAll();
        $this->assertEquals(collect($job)->count(), $response->count());
    }
}
