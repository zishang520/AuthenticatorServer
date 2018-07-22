<?php

if (!function_exists('base64_safe_encode')) {

    /**
     * [base64_safe_encode]
     * @Author    ZiShang520@gmail.com
     * @DateTime  2018-07-21T13:14:55+0800
     * @copyright (c) ZiShang520 All Rights Reserved
     * @param     [type] $data [description]
     * @return    [type] [description]
     */
    function base64_safe_encode($data)
    {
        return rtrim(strtr($data, '+/', '-_'), '=');
    }
}

if (!function_exists('base64_safe_decode')) {

    /**
     * [base64_safe_decode]
     * @Author    ZiShang520@gmail.com
     * @DateTime  2018-07-21T13:14:42+0800
     * @copyright (c) ZiShang520 All Rights Reserved
     * @param     [type] $data [description]
     * @return    [type] [description]
     */
    function base64_safe_decode($data)
    {
        return str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT);
    }
}

if (!function_exists('safe_encrypt')) {

    /**
     * [safe_encrypt 加密]
     * @Author    ZiShang520@gmail.com
     * @DateTime  2018-07-21T13:22:44+0800
     * @copyright (c) ZiShang520 All Rights Reserved
     * @param     [type] $data [description]
     * @param     [type] $key [description]
     * @param     string $cipher [description]
     * @return    [type] [description]
     */
    function safe_encrypt($data, $key, $cipher = 'AES-128-CBC')
    {
        $iv = random_bytes(openssl_cipher_iv_length($cipher));
        $value = openssl_encrypt($data, $cipher, $key, 0, $iv);
        if ($value === false) {
            return false;
        }
        $iv = bin2hex($iv);
        $mac = hash_hmac('sha256', $iv . $value, $key);
        $json = json_encode(compact('iv', 'value', 'mac'));
        if (json_last_error() !== JSON_ERROR_NONE) {
            return false;
        }
        return base64_encode($json);
    }
}

if (!function_exists('safe_decrypt')) {
    /**
     * [safe_decrypt 解密]
     * @Author    ZiShang520@gmail.com
     * @DateTime  2018-07-21T13:23:04+0800
     * @copyright (c) ZiShang520 All Rights Reserved
     * @param     [type] $data [description]
     * @param     [type] $key [description]
     * @param     string $cipher [description]
     * @return    [type] [description]
     */
    function safe_decrypt($data, $key, $cipher = 'AES-128-CBC')
    {
        $payload = json_decode(base64_decode($data), true);
        if (!(is_array($payload) && isset($payload['iv'], $payload['value'], $payload['mac']) && strlen(hex2bin($payload['iv'])) === openssl_cipher_iv_length($cipher))) {
            return false;
        }
        $bytes = random_bytes(16);
        if (!hash_equals(hash_hmac('sha256', $payload['mac'], $bytes, true), hash_hmac('sha256', hash_hmac('sha256', $payload['iv'] . $payload['value'], $key), $bytes, true))) {
            return false;
        }
        $iv = hex2bin($payload['iv']);
        $decrypted = openssl_decrypt($payload['value'], $cipher, $key, 0, $iv);
        return $decrypted;
    }
}

if (!function_exists('gen_sign')) {
    /**
     * [gen_sign 生成sign]
     * @Author    ZiShang520@gmail.com
     * @DateTime  2018-07-22T11:56:54+0800
     * @copyright (c) ZiShang520 All Rights Reserved
     * @param     array $data [description]
     * @param     [type] $key [description]
     * @return    [type] [description]
     */
    function gen_sign(array $data, $key)
    {
        ksort($data);
        return hash_hmac('sha1', json_encode($data), $key);
    }
}

if (!function_exists('check_sign')) {
    /**
     * [check_sign 测试签名]
     * @Author    ZiShang520@gmail.com
     * @DateTime  2018-07-22T11:56:54+0800
     * @copyright (c) ZiShang520 All Rights Reserved
     * @param     array $data [description]
     * @param     [type] $key [description]
     * @return    [type] [description]
     */
    function check_sign(array $data, $key, $sign = '')
    {
        $sign = empty($sign) ? $data['sign'] : $sign;
        unset($data['sign']);
        ksort($data);
        $bytes = random_bytes(16);
        return hash_equals(hash_hmac('sha256', $sign, $bytes, true), hash_hmac('sha256', hash_hmac('sha1', json_encode($data), $key), $bytes, true));
    }
}
