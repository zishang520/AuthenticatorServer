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
        // var_export(Cache::put('bar', 'baz', 30));
        // var_export(Cache::get('bar'));
        return response($user);
    }

    public function login()
    {
        $user = Users::updateOrCreate(['uid' => hash_hmac('sha1', 'oCJUswyqG0fw5997L9wCQ9AjQwIw', 'oCJUswyqG0fw5997L9wCQ9AjQwIw')], []);
        return response(['token' => Crypt::encrypt($user)]);
    }
}
