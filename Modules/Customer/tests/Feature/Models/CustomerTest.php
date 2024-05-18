<?php

namespace Customer\Tests\Feature\Models;

use Customer\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic Database test.
     */
    public function testInsertData(): void
    {
        $data = Customer::factory()->make()->toArray();

        Customer::query()->create($data);

        $this->assertDatabaseHas('customers', $data);
    }
}
