<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api/v1', 'middleware' => 'auth'], function () use ($router) {
    $router->get('points/inrad',  ['uses' => 'PointsController@showAllPointsInRadius']);

    $router->get('points/in/{city}', ['uses' => 'PointsController@showAllPointsInCity']);

    $router->post('points', ['uses' => 'PointsController@create']);

    $router->put('points/{id}', ['uses' => 'PointsController@update']);

    $router->get('health', ['uses' => 'HealthController@showStatus']);
});
