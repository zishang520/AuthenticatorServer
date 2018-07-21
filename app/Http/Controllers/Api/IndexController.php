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
    public function login(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, ['code' => 'bail|required|string'], ['code.required' => 'Oauth_code不能为空', 'code.string' => 'Oauth_code只能是字符串']);
            if ($validator->fails()) {
                return self::dump(20001, $validator->errors()->first());
            }
            if (($data = Wechat::getSessionKey($input['code'])) === false) {
                return self::dump(20005, Wechat::getError()['errMsg']);
            }
            $openid = $data['openid'] ?? null;
            if (empty($openid)) {
                return self::dump(20005, 'OPENID获取失败');
            }
            $session_key = $data['session_key'] ?? null;
            if (empty($session_key)) {
                return self::dump(20005, 'SESSION_KEY获取失败');
            }
            if (($encrypt_session_key = safe_encrypt($session_key, md5($input['code']))) === false) {
                return self::dump(20005, 'SESSION_KEY加密失败');
            }
            $user = Users::updateOrCreate(['uid' => hash_hmac('sha1', $openid, md5($openid))], ['last_login_time' => date('Y-m-d H:i:s')])->toArray();
            $token = base64_safe_encode(Crypt::encrypt($user));
            Cache::put($user['uid'], $token, 1440); // 缓存一份用作为单用户登录
            return self::dump(0, '获取成功', [
                'session_key' => $encrypt_session_key,
                'token' => $token
            ]);
        } catch (\Exception $e) {
            return self::dump(20005, '数据记录出现错误');
        }
    }

    public function detil(Request $request)
    {
        var_dump($request->user());
    }
}
