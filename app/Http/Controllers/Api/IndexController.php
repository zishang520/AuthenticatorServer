<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Model\SafetyData;
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
            if (($encrypt_session_key = safe_encrypt($session_key, md5($input['code'], true))) === false) {
                return self::dump(20005, 'SESSION_KEY加密失败');
            }
            $user = Users::updateOrCreate(['uid' => hash_hmac('sha1', $openid, md5($openid))], ['last_login_time' => date('Y-m-d H:i:s')])->toArray();
            $token = base64_safe_encode(Crypt::encrypt($user));
            Cache::put($user['uid'], $token, 1440); // 缓存一份用作为单用户登录
            return self::dump(0, '获取成功', [
                'session_key' => $encrypt_session_key,
                'token' => $token,
            ]);
        } catch (\Exception $e) {
            return self::dump(20005, '数据记录出现错误');
        }
    }

    /**
     * [get_secure_data 获取云数据]
     * @Author    ZiShang520@gmail.com
     * @DateTime  2018-07-21T18:03:17+0800
     * @copyright (c) ZiShang520 All Rights Reserved
     * @param     Request $request [description]
     * @return    [type] [description]
     */
    public function get_secure_data(Request $request)
    {
        try {
            return self::dump(0, '获取成功', SafetyData::where('user_uid', $request->user()->uid)->first());
        } catch (\Exception $e) {
            return self::dump(20005, '数据记录读取出现错误');
        }
    }

    /**
     * [put_secure_data 跟新或者创建云数据]
     * @Author    ZiShang520@gmail.com
     * @DateTime  2018-07-21T18:03:34+0800
     * @copyright (c) ZiShang520 All Rights Reserved
     * @param     Request $request [description]
     * @return    [type] [description]
     */
    public function put_secure_data(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, ['encrypt_data' => 'bail|required|string', 'is_independentpass' => 'bail|required|boolean'], ['encrypt_data.required' => 'ENCRYPT_DATA不能为空', 'encrypt_data.string' => 'ENCRYPT_DATA只能是字符串', 'is_independentpass.required' => '密码状态不能为空', 'is_independentpass.boolean' => '密码状态只能是一个布尔值']);
            if ($validator->fails()) {
                return self::dump(20001, $validator->errors()->first());
            }
            return self::dump(0, '更新数据成功', SafetyData::updateOrCreate(['user_uid' => $request->user()->uid], ['encrypt_data' => $input['encrypt_data'], 'is_independentpass' => $input['is_independentpass']]));
        } catch (\Exception $e) {
            return self::dump(20005, '更新数据失败');
        }
    }
}
