<?php

namespace Console;

use Customer\Models\Customer;
use EventSource\Database\Seeders\SettingSeeder;
use EventSource\Enums\ActionEnum;
use EventSource\Models\EventSource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\Uid\Ulid;
use Tests\TestCase;

class CustomerActionConsoleTest extends TestCase
{
    use RefreshDatabase;

    public function testHandleStoredEventsCommand(): void
    {

        $this->seed(SettingSeeder::class);
        //act
        $this->post(route('customers.store'), [
            'ulid' => Ulid::generate(),
            'firstname' => 'John',
            'lastname' => 'Doe',
            'dateOfBirth' => '1990-01-01',
            'phoneNumber' => '1-425-280-7778',
            'email' => 'mohamadalinzm@gmail.com',
            'bankAccountNumber' => '3589136294099440'
            ])
        ->assertOk();

        $this->post(route('customers.store'), [
            'ulid' => Ulid::generate(),
            'firstname' => 'Johnny',
            'lastname' => 'Doe2',
            'dateOfBirth' => '1990-01-03',
            'phoneNumber' => '1-425-280-7778',
            'email' => 'mohamadalinm@gmail.com',
            'bankAccountNumber' => '3589136294099440'
        ])
            ->assertOk();


        $this->artisan('customers:handle-stored-events')
            ->assertSuccessful();

        $this->assertDatabaseCount('event_sources', 2);
        $this->assertDatabaseCount('customers', 2);

        $this->patch(route('customers.update-dateOfBirth'), [
            'customerId' => Customer::query()->first()->id,
            'dateOfBirth' => '1991-01-03'
            ])
            ->assertOk();

        $this->patch(route('customers.update-email'), [
            'customerId' => Customer::query()->orderBy('id','desc')->first()->id,
            'email' => 'test@test.com'
        ])
        ->assertOk();

        $this->artisan('customers:handle-stored-events')
            ->assertSuccessful();

        $this->assertDatabaseCount('event_sources', 4);

        $this->assertDatabaseHas('event_sources', [
            'action' => ActionEnum::UPDATE->value,
            'request_body->customerId' => Customer::query()->first()->id,
            'request_body->dateOfBirth' => '1991-01-03'
        ]);

        $this->assertDatabaseHas('event_sources', [
            'action' => ActionEnum::UPDATE->value,
            'request_body->customerId' => Customer::query()->orderBy('id','desc')->first()->id,
            'request_body->email' => 'test@test.com'
        ]);

        $this->delete(route('customers.destroy'), [
            'customerId' => Customer::query()->first()->id
        ])
        ->assertOk();
        $this->delete(route('customers.destroy'), [
            'customerId' => Customer::query()->orderBy('id','desc')->first()->id,
        ])
        ->assertOk();

        $this->artisan('customers:handle-stored-events')
            ->assertSuccessful();

        $this->assertDatabaseCount('event_sources', 6);
        $this->assertDatabaseCount('customers', 0);
    }

}
