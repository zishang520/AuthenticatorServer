<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Support\Facades\Validator;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if ($this->auth->guard($guard)->guest()) {
            return response()->json([
                'code' => 40001,
                'msg' => '身份验证失败',
                'data' => null
            ]);
        }
        if ($request->user()->status == 0) {
            return response()->json([
                'code' => 40002,
                'msg' => '账户被禁用',
                'data' => null
            ]);
        }
        $input = $request->all();
        $validator = Validator::make($input, ['t' => 'bail|required|string', 'sign' => 'bail|required|string']);
        if ($validator->fails() || (((int) $input['t']) <= time()) || (!check_sign($input, $request->user()->uid))) {
            return response()->json([
                'code' => 20001,
                'msg' => '参数错误', // 签名错误或者请求失效
                'data' => null
            ]);
        }
        return $next($request);
    }
}
