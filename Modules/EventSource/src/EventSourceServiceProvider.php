<?php

namespace EventSource;

use EventSource\Repositories\EventSourceRepository;
use EventSource\Repositories\EventSourceRepositoryContract;
use EventSource\Repositories\SettingRepository;
use EventSource\Repositories\SettingRepositoryContract;

class EventSourceServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->app->bind(EventSourceRepositoryContract::class, EventSourceRepository::class);
        $this->app->bind(SettingRepositoryContract::class, SettingRepository::class);
    }

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

    }

}
