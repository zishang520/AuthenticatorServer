<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Model\Users;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class IndexController extends ApiController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index()
    {
        $user = Users::updateOrCreate(['uid' => hash_hmac('sha1', 'oCJUswyqG0fw5997L9wCQ9AjQwIw', 'oCJUswyqG0fw5997L9wCQ9AjQwIw')], []);
        Cache::store('redis')->set('bar', 'baz');
        var_dump(Cache::store('redis')->get('bar'));
        return response($user);
    }

    public function login()
    {
        $user = Users::updateOrCreate(['uid' => hash_hmac('sha1', 'oCJUswyqG0fw5997L9wCQ9AjQwIw', 'oCJUswyqG0fw5997L9wCQ9AjQwIw')], []);
        return response(['token' => Crypt::encrypt($user)]);
    }
}
