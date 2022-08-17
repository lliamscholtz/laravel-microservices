<?php

use Illuminate\Http\Request;
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
    '/hello',
    function () {
        return new Response('Hello from Gateway!');
    }
);

Route::get(
    '/microservice-a',
    function () {
        $response = Http::get('http://microservice-a/api/service');
        return new Response($response->body(), $response->status());
    }
);
Route::get(
    '/microservice-a/passthru',
    function () {
        $response = Http::get('http://microservice-a/api/passthru');
        return new Response($response->body(), $response->status());
    }
);

Route::get(
    '/microservice-b',
    function (Request $request) {
        $bearer = $request->bearerToken();
        $response = Http::withToken($bearer)
            ->get('http://microservice-b/api/service');
        return new Response($response->body(), $response->status());
    }
)->middleware(['client:microservice-b']);

Route::get(
    '/microservice-c',
    function (Request $request) {
        $bearer = $request->bearerToken();
        $response = Http::withToken($bearer)
            ->get('http://microservice-c/api/service');
        return new Response($response->body(), $response->status());
    }
)->middleware(['client:microservice-c']);
