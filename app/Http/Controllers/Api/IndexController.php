<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Model\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use luoyy\Wechat\Facades\Wechat;

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
        var_export(Cache::get('bar'));
        return response($user);
    }

    public function login()
    {
        $user = Users::updateOrCreate(['uid' => hash_hmac('sha1', 'oCJUswyqG0fw5997L9wCQ9AjQwIw', 'oCJUswyqG0fw5997L9wCQ9AjQwIw')], []);
        return response(['token' => Crypt::encrypt($user)]);
    }

    public function test(Request $request)
    {
        $data = $this->validate($request, ['code' => 'required|string'], ['code.required' => '不合法的Oauth_code', 'code.string' => 'Oauth_code只能是字符串']);
        var_dump(Wechat::getSessionKey($data['code']));
        var_dump(Wechat::getError());
    }
}
