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
        $router->get('/',  ['uses' => 'ATMController@getAllATMs']);

        $router->get('/{id}', ['uses' => 'ATMController@getATM']);
    });

    $router->group(['prefix' => 'banks'], function () use ($router) {

        $router->post('', ['uses' => 'BankController@create']);

        $router->get('plans', ['uses' => 'SubscriptionsController@getPlans']);

        $router->post('login', ['uses' => 'BankController@login']);

        $router->group(['middleware' => 'auth:bank', 'prefix' => 'me'], function () use ($router) {

            $router->group([''], function () use ($router) {

                $router->get('',['uses' => 'BankController@me']);

                $router->put('', ['uses' => 'BankController@update']);

                $router->delete('', ['uses' => 'BankController@delete']);

            });

            $router->post('logout', ['uses' => 'BankController@logout']);

            $router->post('refresh', 'BankController@refresh');

            $router->group(['prefix' => 'managers'], function () use ($router) {

                $router->get('/', ['uses' => 'BankController@getManagers']);

                $router->post('/', ['uses' => 'BankController@createManager']);

                $router->get('/{id}', ['uses' => 'BankController@getManager']);

                $router->put('/{id}', ['uses' => 'BankController@updateManager']);

                $router->delete('/{id}', ['uses' => 'BankController@deleteManager']);

            });

            $router->group(['prefix' => 'atms'], function () use ($router) {

                $router->get('/', ['uses' => 'BankController@getATMs']);

                $router->post('/', ['uses' => 'BankController@createATM']);

                $router->get('/{id}', ['uses' => 'BankController@getATM']);

                $router->delete('/{id}', ['uses' => 'BankController@deleteATM']);

                $router->put('/{id}', ['uses' => 'BankController@updateATM']);

            });

            $router->group(['prefix' => 'branches'], function () use ($router) {

                $router->get('/', ['uses' => 'BankController@getBranches']);

                $router->post('/', ['uses' => 'BankController@createBranch']);

                $router->get('/{id}', ['uses' => 'BankController@getBranch']);

                $router->delete('/{id}', ['uses' => 'BankController@deleteBranch']);

                $router->put('/{id}', ['uses' => 'BankController@updateBranch']);

            });

        });
    });

    $router->group(['prefix' => 'managers'], function () use ($router) {

        $router->post('login', ['uses' => 'ManagerController@login']);

        $router->group(['middleware' => 'auth:manager', 'prefix' => 'me'], function () use ($router) {

            $router->group([''], function () use ($router) {

                $router->get('',['uses' => 'ManagerController@me']);

                $router->put('', ['uses' => 'ManagerController@update']);

            });

            $router->post('logout', ['uses' => 'ManagerController@logout']);

            $router->post('refresh', 'ManagerController@refresh');

            $router->group(['prefix' => 'atms'], function () use ($router) {

                $router->get('/', ['uses' => 'ManagerController@getMyATMS']);

                $router->put('/{id}', ['uses' => 'ManagerController@updateATM']);

            });

        });

    });

});