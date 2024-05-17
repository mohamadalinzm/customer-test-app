<?php

namespace Tests\Feature\Controllers;

use Customer\Models\Customer;
use EventSource\Enums\ActionEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexMethod(): void
    {
        //arrange:
        Customer::factory()->count(10)->create();
        //act
        $response = $this->get(route('customers.index'));
        //assert
        $response->assertOk()
            ->assertJson([
                'status' => true,
                'data' => Customer::query()->latest()->get()->toArray(),
                'message' => 'Customers fetched successfully.',
            ]);
    }

    public function testStoreMethod(): void
    {
        //arrange
        $customer = Customer::factory()->make()->toArray();
        //act
        $response = $this->post(route('customers.store'), $customer);
        //assert
        $response->assertOk()
            ->assertJson([
                'status' => true,
                'data' => $customer['ulid'],
                'message' => 'Customer created successfully.',
            ]);

        $this->assertDatabaseCount('event_sources', 1);
        $this->assertDatabaseHas('event_sources', [
            'action' => ActionEnum::STORE->value,
            'request_body->ulid' => $customer['ulid'],
            'request_body->firstname' => $customer['firstname'],
            'request_body->lastname' => $customer['lastname'],
            'request_body->dateOfBirth' => $customer['dateOfBirth'],
            'request_body->phoneNumber' => $customer['phoneNumber'],
            'request_body->email' => $customer['email'],
            'request_body->bankAccountNumber' => $customer['bankAccountNumber'],
        ]);
    }

    public function testUpdateFirstname(): void
    {
        //arrange
        $customer = Customer::factory()->create();
        $firstname = 'Jane';
        //act
        $response = $this->patch(route('customers.update-firstname'), [
            'customerId' => $customer->id,
            'firstname' => $firstname,
        ]);
        //assert
        $response->assertOk()
            ->assertJson([
                'status' => true,
                'data' => $customer->ulid,
                'message' => 'Customer updated successfully.',
            ]);

        $this->assertDatabaseHas('event_sources', [
            'action' => ActionEnum::UPDATE->value,
            'request_body->customerId' => $customer->id,
            'request_body->firstname' => $firstname,
        ]);
    }

    public function testUpdateLastname(): void
    {
        //arrange
        $customer = Customer::factory()->create();
        $lastname = 'Wick';
        //act
        $response = $this->patch(route('customers.update-lastname'), [
            'customerId' => $customer->id,
            'lastname' => $lastname,
        ]);
        //assert
        $response->assertOk()
            ->assertJson([
                'status' => true,
                'data' => $customer->ulid,
                'message' => 'Customer updated successfully.',
            ]);

        $this->assertDatabaseHas('event_sources', [
            'action' => ActionEnum::UPDATE->value,
            'request_body->customerId' => $customer->id,
            'request_body->lastname' => $lastname,
        ]);
    }

    public function testUpdateDateOfBirth(): void
    {
        //arrange
        $customer = Customer::factory()->create();
        $dateOfBirth = '04/30/2000';
        //act
        $response = $this->patch(route('customers.update-dateOfBirth'), [
            'customerId' => $customer->id,
            'dateOfBirth' => $dateOfBirth,
        ]);
        //assert
        $response->assertOk()
            ->assertJson([
                'status' => true,
                'data' => $customer->ulid,
                'message' => 'Customer updated successfully.',
            ]);

        $this->assertDatabaseHas('event_sources', [
            'action' => ActionEnum::UPDATE->value,
            'request_body->customerId' => $customer->id,
            'request_body->dateOfBirth' => $dateOfBirth,
        ]);
    }

    public function testUpdatePhoneNumber(): void
    {
        //arrange
        $customer = Customer::factory()->create();
        $phoneNumber = '09123456789';
        //act
        $response = $this->patch(route('customers.update-phoneNumber'), [
            'customerId' => $customer->id,
            'phoneNumber' => $phoneNumber,
        ]);
        //assert
        $response->assertOk()
            ->assertJson([
                'status' => true,
                'data' => $customer->ulid,
                'message' => 'Customer updated successfully.',
            ]);

        $this->assertDatabaseHas('event_sources', [
            'action' => ActionEnum::UPDATE->value,
            'request_body->customerId' => $customer->id,
            'request_body->phoneNumber' => $phoneNumber,
        ]);
    }

    public function testUpdateEmailNumber(): void
    {
        //arrange
        $customer = Customer::factory()->create();
        $email = 'test@test.com';
        //act
        $response = $this->patch(route('customers.update-email'), [
            'customerId' => $customer->id,
            'email' => $email,
        ]);
        //assert
        $response->assertOk()
            ->assertJson([
                'status' => true,
                'data' => $customer->ulid,
                'message' => 'Customer updated successfully.',
            ]);

        $this->assertDatabaseHas('event_sources', [
            'action' => ActionEnum::UPDATE->value,
            'request_body->customerId' => $customer->id,
            'request_body->email' => $email,
        ]);
    }

    public function testUpdateBankAccountNumber(): void
    {
        //arrange
        $customer = Customer::factory()->create();
        $bankAccountNumber = '3589136294099440';
        //act
        $response = $this->patch(route('customers.update-bankAccountNumber'), [
            'customerId' => $customer->id,
            'bankAccountNumber' => $bankAccountNumber,
        ]);
        //assert
        $response->assertOk()
            ->assertJson([
                'status' => true,
                'data' => $customer->ulid,
                'message' => 'Customer updated successfully.',
            ]);

        $this->assertDatabaseHas('event_sources', [
            'action' => ActionEnum::UPDATE->value,
            'request_body->customerId' => $customer->id,
            'request_body->bankAccountNumber' => $bankAccountNumber,
        ]);
    }

    public function testDeleteMethod(): void
    {
        //arrange
        $customer = Customer::factory()->create();
        //act
        $response = $this->delete(route('customers.destroy'), ['customerId' => $customer->id]);
        //assert
        $response->assertOk()
            ->assertJson([
                'status' => true,
                'data' => $customer->ulid,
                'message' => 'Customer deleted successfully.',
            ]);

        $this->assertDatabaseHas('event_sources', [
            'action' => ActionEnum::DELETE->value,
            'request_body->customerId' => $customer->id,
        ]);
    }

    public function testInvalidEmailValidation(): void
    {
        //arrange
        $customer = Customer::factory()->state([
            'email' => 'invalid-email',
        ])->make()->toArray();
        //act
        $response = $this->post(route('customers.store'), $customer);
        //assert
        $response->assertStatus(302)
            ->assertInvalid('email');
    }

    public function testNullEmailValidation(): void
    {
        //arrange
        $customer = Customer::factory()->state([
            'email' => null,
        ])->make()->toArray();
        //act
        $response = $this->post(route('customers.store'), $customer);
        //assert
        $response->assertStatus(302)
            ->assertInvalid('email');
    }

    public function testInvalidPhoneNumberValidation(): void
    {
        //arrange
        $customer = Customer::factory()->state([
            'phoneNumber' => 'invalid-phone-number',
        ])->make()->toArray();
        //act
        $response = $this->post(route('customers.store'), $customer);
        //assert
        $response->assertStatus(302)
            ->assertInvalid('phoneNumber');
    }

    public function testNullPhoneNumberValidation(): void
    {
        //arrange
        $customer = Customer::factory()->state([
            'phoneNumber' => null,
        ])->make()->toArray();
        //act
        $response = $this->post(route('customers.store'), $customer);
        //assert
        $response->assertStatus(302)
            ->assertInvalid('phoneNumber');
    }

    public function testInvalidBirthDayValidation(): void
    {
        //arrange
        $customer = Customer::factory()->state([
            'dateOfBirth' => 'invalid-dateOfBirth',
        ])->make()->toArray();
        //act
        $response = $this->post(route('customers.store'), $customer);
        //assert
        $response->assertStatus(302)
            ->assertInvalid('dateOfBirth');
    }

    public function testNullBirthDayValidation(): void
    {
        //arrange
        $customer = Customer::factory()->state([
            'dateOfBirth' => null,
        ])->make()->toArray();
        //act
        $response = $this->post(route('customers.store'), $customer);
        //assert
        $response->assertStatus(302)
            ->assertInvalid('dateOfBirth');
    }

    public function testInvalidCardNumberValidation(): void
    {
        //arrange
        $customer = Customer::factory()->state([
            'bankAccountNumber' => 'invalid-card-number',
        ])->make()->toArray();
        //act
        $response = $this->post(route('customers.store'), $customer);
        //assert
        $response->assertStatus(302)
            ->assertInvalid('bankAccountNumber');
    }

    public function testNullCardNumberValidation(): void
    {
        //arrange
        $customer = Customer::factory()->state([
            'bankAccountNumber' => null,
        ])->make()->toArray();
        //act
        $response = $this->post(route('customers.store'), $customer);
        //assert
        $response->assertStatus(302)
            ->assertInvalid('bankAccountNumber');
    }

    public function testDuplicateCustomerValidation(): void
    {
        //arrange
        $customer = Customer::factory()->count(2)->state([
            'firstname' => 'test',
            'lastname' => 'test',
            'dateOfBirth' => '04/30/2024',
        ])->make()->toArray();
        //act
        $response = $this->post(route('customers.store'), $customer);
        //assert
        $response->assertStatus(302)
            ->assertInvalid('firstname');
    }
}
