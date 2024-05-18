<?php

namespace Customer;

use Customer\Console\Commands\HandleStoredEvents;
use Customer\Repositories\ReadCustomerRepository;
use Customer\Repositories\ReadCustomerRepositoryContract;
use Customer\Repositories\WriteCustomerRepository;
use Customer\Repositories\WriteCustomerRepositoryContract;

class CustomerServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {

        $singletons = [
            WriteCustomerRepositoryContract::class => WriteCustomerRepository::class,
            ReadCustomerRepositoryContract::class => ReadCustomerRepository::class,
        ];

        foreach ($singletons as $abstract => $concrete) {
            $this->app->singleton(
                $abstract,
                $concrete,
            );
        }

    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->commands([
            HandleStoredEvents::class,
        ]);

    }
}
