<?php
namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class ApiController extends BaseController
{
    /**
     * [dump Api数据输出]
     * @Author    ZiShang520@gmail.com
     * @DateTime  2018-07-20T12:05:02+0800
     * @copyright (c) ZiShang520 All Rights Reserved
     * @param     [type] $code [状态吗]
     * @param     [type] $msg [信息]
     * @param     [type] $data [数据]
     * @return    [type] [Response对象]
     */
    protected static function dump($code, $msg, $data = null)
    {
        return response()->json([
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ]);
    }
}
