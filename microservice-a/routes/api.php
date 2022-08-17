<?php

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get(
    '/service',
    function () {
        return new Response('Hello from Microservice A!');
    }
);

Route::get(
    '/passthru',
    function () {
        return Http::get('http://microservice-c/api/service');
    }
);
