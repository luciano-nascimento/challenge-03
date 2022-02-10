<?php

namespace Tests\Feature\Controllers;

use App\Models\Job;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class JobControllerTest extends TestCase
{

    use DatabaseMigrations;

    const HTTP_UNPROCESSABLE_ENTITY = 422;
    const HTTP_CREATED = 201;
    const HTTP_OK = 200;
    
    public function testShouldStoreJob()
    {
        $response = $this->postJson('api/jobs', [ 'title' => 'title', 'description' => 'description']);

        $response
            ->assertStatus(self::HTTP_CREATED)
            ->assertJsonPath('title', 'title');    
    }

    public function testStoreShouldThrowAnErrorIfParametersIsMissing()
    {
        $response = $this->postJson('api/jobs', []);

        $response
            ->assertStatus(self::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonPath('message', 'The given data was invalid.');    
    }

    public function testIndexShouldReturnAllJobs()
    {
        Job::create(['title' => 'title', 'description' => 'desc']);
        Job::create(['title' => 'title2', 'description' => 'desc2']);
        $response = $this->getJson('api/jobs', []);

        $response
            ->assertStatus(self::HTTP_OK)
            ->assertJsonCount(2);    
    }
}
