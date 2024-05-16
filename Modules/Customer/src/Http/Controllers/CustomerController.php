<?php

namespace Customer\Http\Controllers;

use App\Http\Controllers\Controller;
use Customer\Http\Requests\CustomerDeleteRequest;
use Customer\Http\Requests\CustomerStoreRequest;
use Customer\Http\Requests\CustomerUpdateRequest;
use Customer\Repositories\ReadCustomerRepository;
use EventSource\Enums\ActionEnum;
use EventSource\Repositories\EventSourceRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class CustomerController extends Controller
{
    //Seperated Read and Write Repositories and injected them in constructor
    public function __construct(protected ReadCustomerRepository $readCustomerRepository,
                                protected EventSourceRepository $eventSourceRepository)
    {
    }

    public function index(): JsonResponse
    {
        //Get All Customers
        $customers = $this->readCustomerRepository->index();

        return Response::json(
            [
                'status' => true,
                'data' => $customers,
                'message' => 'Customers fetched successfully.',
            ]
        );
    }

    public function store(CustomerStoreRequest $request): JsonResponse
    {

        //create record for store customer in event_sources table
        $event = $this->eventSourceRepository->store([
            'action' => ActionEnum::STORE->value,
            'request_body' => $request->all(),
        ]);


        return Response::json(
            [
                'status' => true,
                'data' => $event->request_body['ulid'],
                'message' => 'Customer created successfully.',
            ]
        );
    }

    public function update(CustomerUpdateRequest $request): JsonResponse
    {
        $customer = $this->readCustomerRepository->find($request->customerId);
        //create record for update customer in event_sources table
        $this->eventSourceRepository->store([
            'action' => ActionEnum::UPDATE->value,
            'request_body' => $request->all(),
        ]);

        return Response::json(
            [
                'status' => true,
                'data' => $customer->ulid,
                'message' => 'Customer updated successfully.',
            ]
        );
    }

    public function destroy(CustomerDeleteRequest $request): JsonResponse
    {
        $customer = $this->readCustomerRepository->find($request->customerId);
        //create record for delete customer in event_sources table
        $this->eventSourceRepository->store([
            'action' => ActionEnum::DELETE->value,
            'request_body' => $request->all(),
        ]);

        return Response::json(
            [
                'status' => true,
                'data' => $customer->ulid,
                'message' => 'Customer deleted successfully.',
            ]
        );
    }
}
