<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;

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
        return response(['test' => 'aaa']);
    }

    //
}
