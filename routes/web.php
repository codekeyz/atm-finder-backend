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

$router->group(['prefix' => '/'],function () use ($router) {

    $router->get('/', function () use ($router) {
        return $router->app->version();
    });

    $router->group(['prefix' => 'atms'], function () use ($router) {
        $router->get('/',  ['uses' => 'ATMController@getOneOrAllATMs']);

        $router->post('/', ['uses' => 'ATMController@create']);

        $router->delete('/{id}', ['uses' => 'ATMController@delete']);

        $router->put('/{id}', ['uses' => 'ATMController@update']);
    });

    $router->group(['prefix' => 'banks'], function () use ($router) {

        $router->post('/', ['uses' => 'BankController@create']);

        $router->post('/login', ['uses' => 'BankController@login']);

        $router->group(['middleware' => 'auth:api', 'prefix' => 'me'], function () use ($router) {

            $router->post('', ['uses' => 'BankController@me']);

            $router->post('logout', ['uses' => 'BankController@logout']);

            $router->post('refresh', 'AuthController@refresh');

            $router->get('managers', ['uses' => 'BankController@getManagers']);

            $router->get('atms', ['uses' => 'BankController@getATMs']);

        });
    });

});