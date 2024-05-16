<?php

namespace EventSource\Repositories;

use EventSource\Models\Setting;

interface SettingRepositoryContract
{

    public function getLastEventId() : int;
    public function store($request) : Setting;

    public function update($request) : int;
}
