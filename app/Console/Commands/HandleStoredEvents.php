<?php

namespace App\Console\Commands;

use Customer\Repositories\ReadCustomerRepositoryContract;
use Customer\Repositories\WriteCustomerRepositoryContract;
use EventSource\Enums\ActionEnum;
use EventSource\Repositories\EventSourceRepositoryContract;
use EventSource\Repositories\SettingRepositoryContract;
use Illuminate\Console\Command;

class HandleStoredEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customers:handle-stored-events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Customers based on event that record in the event_sources table';

    public function __construct(protected EventSourceRepositoryContract $eventSourceRepository,
                                protected ReadCustomerRepositoryContract $readCustomerRepository,
                                protected WriteCustomerRepositoryContract $writeCustomerRepository,
                                protected SettingRepositoryContract $settingRepository)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //read unhandled events and apply actions on customers table
        $lastId = $this->settingRepository->getLastEventId();
        $unHandledEvents = $this->eventSourceRepository->getUnhandledRequests($lastId)->each(function($event){

            if ($event->action->value == ActionEnum::UPDATE->value)
            {
                $customer = $this->readCustomerRepository->find($event->request_body['customerId']);
                $data = $event->request_body;
                unset($data['customerId']);
                $this->writeCustomerRepository->{$event->action->value}($customer, $data);
            }elseif ($event->action->value == ActionEnum::DELETE->value)
            {
                $customer = $this->readCustomerRepository->find($event->request_body['customerId']);
                $this->writeCustomerRepository->{$event->action->value}($customer);
            }
            else{
                $this->writeCustomerRepository->{$event->action->value}($event->request_body);
            }


        });

        //update last event id on settings table
        $this->settingRepository->update(['key' => 'last_event_id', 'value' => $unHandledEvents->last()->id]);
    }
}
