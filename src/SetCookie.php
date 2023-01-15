<?php

/**
 * author      : Administrator
 * creatTime   : 2022/11/15 21:53
 * description :
 */

namespace Ibazhe\Cookies;

/**
 * 单条set-cookie对象
 */
class SetCookie
{
    public $Name = null;
    public $Value = null;
    public $Expires = 253370736000;//本对象中无到期时间的默认为253370736000，实际无到期时间的为会话期的cookie，也就是关闭浏览器后就清理掉了
    public $Domain = null;
    public $Path = '/';
    public $SameSite = 'Lax';
    public $Secure = true;
    public $HttpOnly = FALSE;

    /**
     * @param $set_cookie_str string headers中的单条set-cookie
     * @param $domain         string 所属的域名
     */
    public function __construct($set_cookie_str = null, $domain = null) {
        if ($set_cookie_str === null) {
            return;
        }
        //判断是否包含Set-Cookie:
        if (stripos($set_cookie_str, "Set-Cookie:") !== false) {
            $header_name_offset = stripos($set_cookie_str, ":");
            $header_name        = substr($set_cookie_str, 0, $header_name_offset);
            $header_value       = substr($set_cookie_str, $header_name_offset + 1);
            if (self::equal($header_name, 'Set-Cookie')) {
                $set_cookie_arr = explode(";", $header_value);
            } else {
                return;
            }
        } else {
            $set_cookie_arr = explode(";", $set_cookie_str);
        }
        $this->Domain = $domain;
        foreach ($set_cookie_arr as $index => $attributes) {
            $attributes     = trim($attributes);
            $attributes_arr = explode("=", $attributes);
            if (count($attributes_arr) === 2) {
                //print_r($attributes_arr);
                $attributes_key   = trim($attributes_arr[0]);
                $attributes_value = trim($attributes_arr[1]);
                //只有第一个键值对才是cookie
                if ($index === 0) {
                    $this->Name  = $attributes_key;
                    $this->Value = $attributes_value;
                } else {
                    if (self::equal($attributes_key, 'Expires')) {
                        //var_dump($attributes_value);
                        $this->Expires = strtotime($attributes_value);
                    } elseif (self::equal($attributes_key, 'Domain')) {
                        $this->Domain = $attributes_value;
                    } elseif (self::equal($attributes_key, 'Path')) {
                        $this->Path = $attributes_value;
                    } elseif (self::equal($attributes_key, 'SameSite')) {
                        $this->SameSite = $attributes_value;
                    }
                }
            } else {
                if (self::equal($attributes, 'Secure')) {
                    $this->Secure = true;
                } elseif (self::equal($attributes, 'HttpOnly')) {
                    $this->HttpOnly = true;
                }
            }
        }

    }

    protected static function equal($str1, $str2) {
        if($str1===null && $str2!==null)return false;
        if($str2===null && $str1!==null)return false;
        if($str2===null && $str1===null)return true;
        return strcasecmp($str1, $str2) == 0;
    }
}