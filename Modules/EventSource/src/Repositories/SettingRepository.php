<?php

namespace EventSource\Repositories;

use EventSource\Models\EventSource;
use EventSource\Models\Setting;
use ReflectionClass;

class SettingRepository implements SettingRepositoryContract
{

    //Get last event id from settings table
    public function getLastEventId(): int
    {
        return Setting::query()->where('key', 'last_event_id')->first()->value;
    }

    //Store new configuration in settings table
    public function store($request): Setting
    {
        return Setting::create([
            'key' => $request['key'],
            'value' => $request['value'],
        ]);
    }

    //update last event id on settings table
    public function update($request) : int
    {
        return Setting::query()
            ->where('key', $request['key'])
            ->update(['value' => $request['value']]);
    }
}
