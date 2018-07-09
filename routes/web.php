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
$router->group(['namespace' => 'Api'], function () use ($router) {
    $router->get('/', 'IndexController@index');
});

$router->get('/key', function () {
    // var_dump(hash_hmac('sha1','o4_uIw-iO8no-XKmnwa35by3QUpA','o4_uIw-iO8no-XKmnwa35by3QUpA'));
    return base64_encode(md5(sha1(str_random(32), true), true) . md5(sha1(str_random(32), true), true));
});
