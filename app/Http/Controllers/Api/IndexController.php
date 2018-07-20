<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Model\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use luoyy\Wechat\Facades\Wechat;

class IndexController extends ApiController
{
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
        $input = $request->all();
        $validator = Validator::make($input, ['code' => 'bail|required|string'], ['code.required' => 'Oauth_code不能为空', 'code.string' => 'Oauth_code只能是字符串']);
        if ($validator->fails()) {
            return self::dump(20001, $validator->errors()->first());
        }
        if (($data = Wechat::getSessionKey($input['code'])) === false) {
            return self::dump(20005, Wechat::getError()['errText']);
        }
        return self::dump(0, '获取成功', $data);
    }
}
