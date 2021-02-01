<?php

namespace App\Domains\Core\Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Domains\Core\Tests\AppTestCase;

/**
 * @internal
 * @coversNothing
 */
class ExampleAppTest extends AppTestCase
{
    public function testBasicTest(): void
    {
        $response = $this->get('/');

        $response->assertStatus(404);
    }
}
