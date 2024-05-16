<?php

namespace EventSource\Repositories;

use EventSource\Models\EventSource;
use Illuminate\Support\Collection;

interface EventSourceRepositoryContract
{

    public function getUnhandledRequests($lastId): Collection;
    public function store($request) : EventSource;
}
