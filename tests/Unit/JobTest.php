<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Job;

class JobTest extends TestCase
{
    public function testShouldHaveFillableProperlyConfigured()
    {
        $job = new Job();
        $this->assertEquals(
            ['title', 'description'],
            $job->getFillable()
        );
    }
}
