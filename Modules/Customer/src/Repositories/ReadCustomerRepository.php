<?php

namespace Customer\Repositories;

use Customer\Models\Customer;

class ReadCustomerRepository implements ReadCustomerRepositoryContract
{
    public function find(int $id): ?object
    {
        return Customer::query()->find($id);
    }

    public function index(): array
    {
        return Customer::latest()->get()->toArray();
    }
}
