<?php

use App\Ref\CreateProductCommand;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/ref', function () {

    $command = new CreateProductCommand('mamad', 'ali');
    $commandBus = new \App\Ref\CommandBusNew();
    $commandBus->handle($command);

    //    dd($command);
});
