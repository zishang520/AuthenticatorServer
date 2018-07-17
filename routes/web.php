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
$router->group(['namespace' => 'Api', 'middleware' => 'auth', 'prefix' => 'api'], function () use (&$router) {
    $router->get('index', 'IndexController@index');
    $router->get('login', 'IndexController@login');
});
