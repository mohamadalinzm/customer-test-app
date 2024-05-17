<?php

namespace Customer\Repositories;

use Customer\Models\Customer;

interface WriteCustomerRepositoryContract
{
    public function store(array $request): ?Customer;

    public function update(Customer $customer, array $request): bool;

    public function delete(Customer $customer): ?bool;
}
