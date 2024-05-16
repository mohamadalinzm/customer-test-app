<?php

namespace Customer\Repositories;

use Illuminate\Support\Facades\DB;
use Customer\Models\Customer;
use Symfony\Component\Uid\Ulid;

class WriteCustomerRepository implements WriteCustomerRepositoryContract
{

    public function store(array $request): ?Customer
    {
        return Customer::create([
            'ulid' => Ulid::generate(),
            'firstname' => $request['firstname'],
            'lastname' => $request['lastname'],
            'dateOfBirth' => $request['dateOfBirth'],
            'phoneNumber' => $request['phoneNumber'],
            'email' => $request['email'],
            'bankAccountNumber' => $request['bankAccountNumber']
        ]);
    }

    public function update(Customer $customer,array $request): bool
    {
        return $customer->update($request);
    }

    public function delete(Customer $customer): ?bool
    {
        return $customer->delete();
    }
}
