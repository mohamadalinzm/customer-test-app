<?php

namespace Customer\Repositories;

interface ReadCustomerRepositoryContract
{
    public function find(int $id): ?object;

    public function index(): array;
}
