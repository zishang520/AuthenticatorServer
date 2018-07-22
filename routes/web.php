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

// API

$router->group(['namespace' => 'Api', 'prefix' => 'api'], function () use ($router) {
    $router->post('login', 'IndexController@login');
    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->post('get_secure_data', 'IndexController@get_secure_data');
        $router->post('put_secure_data', 'IndexController@put_secure_data');
    });
});
