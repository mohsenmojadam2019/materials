<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_application_boots(): void
    {
        $this->assertSame('fa', config('app.locale'));
        $this->assertSame('Asia/Tehran', config('app.timezone'));
    }
}