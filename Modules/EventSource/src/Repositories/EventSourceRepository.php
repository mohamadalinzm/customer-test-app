<?php

namespace EventSource\Repositories;

use EventSource\Models\EventSource;
use Illuminate\Support\Collection;

class EventSourceRepository implements EventSourceRepositoryContract
{

    public function getUnhandledRequests($lastId): Collection
    {
        return EventSource::query()->where('id','>',$lastId)->get();
    }

    public function store($request): EventSource
    {
        return EventSource::create([
            'action' => $request['action'],
            'request_body' => $request['request_body'],
        ]);
    }
}
