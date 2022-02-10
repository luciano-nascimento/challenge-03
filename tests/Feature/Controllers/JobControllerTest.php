<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class JobControllerTest extends TestCase
{

    use DatabaseMigrations;

    const HTTP_UNPROCESSABLE_ENTITY = 422;
    const HTTP_CREATED = 201;
    
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
}
