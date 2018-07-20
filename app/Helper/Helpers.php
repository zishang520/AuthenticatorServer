<?php

if (!function_exists('base64_safe_encode')) {

/**
 * @param $data
 */
    function base64_safe_encode($data)
    {
        return rtrim(strtr($data, '+/', '-_'), '=');
    }
}

if (!function_exists('base64_safe_decode')) {

/**
 * @param $data
 */
    function base64_safe_decode($data)
    {
        return str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT);
    }
}
