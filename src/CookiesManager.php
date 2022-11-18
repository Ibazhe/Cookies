<?php

/**
 * author      : Administrator
 * creatTime   : 2022/11/15 21:53
 * description :
 */

namespace Ibazhe\Cookies;

use Exception;

class CookiesManager
{
    /**
     * @var SetCookie[] 本对象管理的cookies
     */
    protected $cookies_arr = [];

    /**
     *
     * @param $serialize_cookies string 本对象序列化后的cookies
     */
    public function __construct($serialize_cookies = '') {
        if (!empty($serialize_cookies)) {
            $this->cookies_arr = unserialize($serialize_cookies);
        }
    }

    /**
     * 导出序列化后本对象管理的cookies
     * @return string
     */
    public function exportCookis() {
        return serialize($this->cookies_arr);
    }

    /**
     * 更新cookie，传入响应头
     * @param $headers string 响应头
     * @param $url     string
     * @return void
     * @throws Exception
     */
    public function upH($headers, $url = '') {
        $domain = null;
        if (!empty($url)) {
            self::checkUrl($url);
            $parse  = parse_url($url);
            $domain = $parse['host'];
        }

        $headers_arr = explode("\r\n", $headers);
        foreach ($headers_arr as $header) {
            $header_name_offset = stripos($header, ":");
            $header_name        = substr($header, 0, $header_name_offset);
            $header_value       = substr($header, $header_name_offset + 1);
            if (self::equal($header_name, 'Set-Cookie')) {
                $this->up(new SetCookie($header_value, $domain));
            }
        }
    }

    /**
     * 获取cookie
     * @param $url      string 欲使用此cookies访问的url,为空则获取全部
     * @param $is_xhr   bool 是否为xhr/ajax/js请求
     * @return string
     * @throws Exception
     */
    public function getCookies($url = '', $is_xhr = false) {
        if (!empty($url)) {
            self::checkUrl($url);
            $parse  = parse_url($url);
            $domain = $parse['host'];
            $path   = $parse['path'];
            $secure = $parse['scheme'] == 'https';
        }
        $res_ck = '';
        /**
         * @var $cookie    SetCookie
         */

        foreach ($this->cookies_arr as $index => $cookie) {
            if (!empty($url)) {
                if (!(self::equal($cookie->Domain, $domain) || self::endWith('.' . $domain, $cookie->Domain))) {
                    //var_dump('Domain' . $domain . '|' . $cookie->Domain);
                    continue;
                }
                //如果cookie的路径在请求的路径首部，代表通过
                if (!self::startWith($path, $cookie->Path)) {
                    //var_dump('Path' . $path . '|' . $cookie->Path);
                    continue;
                }
                //如果是xhr请求的话，HttpOnly不能是true,不然不要这个ck。如果不是的话，就无所谓了
                if ($is_xhr && $cookie->HttpOnly) {
                    //var_dump($cookie->Name);
                    continue;
                }

                if ($cookie->Secure != $secure) {
                    //var_dump('Secure' . $secure . '|' . $cookie->Secure);
                    continue;
                }
                if ($cookie->Expires < time()) {
                    //var_dump('time');
                    continue;
                }
            }
            $res_ck .= $cookie->Name . '=' . $cookie->Value . ';';
        }
        //var_dump($res_ck);
        return $res_ck;
    }

    /**
     * 更新/添加cookies(多条/单条)
     * @param $up_cookie SetCookie|SetCookie[]|string   cookies文本(多条用;分割)或者cookie对象(多条传入数组)或者cookie对象数组
     * @return void
     */
    public function up($up_cookie) {
        $up_cookie_arr = [];
        //把cookies文本转成cookie对象数组
        if (is_string($up_cookie)) {
            $cookie_str_arr = explode(';', $up_cookie);
            foreach ($cookie_str_arr as $cookie_str) {
                $temp_arr           = explode('=', $cookie_str);
                $temp_cookie        = new SetCookie();
                $temp_cookie->Name  = trim($temp_arr[0]);
                $temp_cookie->Value = trim($temp_arr[1]);
                $up_cookie_arr[]    = $temp_cookie;
            }
        }
        //把cookie对象转成cookie对象数组
        if ($up_cookie instanceof SetCookie) {
            $up_cookie_arr = [$up_cookie];
        }
        /**
         * @var $temp_up_cookie    SetCookie
         * @var $cookie            SetCookie
         */
        //遍历需要添加的cookie
        foreach ($up_cookie_arr as $temp_up_cookie) {
            //以防重复，遍历现有的cookie，先把之前添加进来的同名cookie删除
            foreach ($this->cookies_arr as $index => $my_cookie) {

                if ($my_cookie->Name == $temp_up_cookie->Name) {
                    unset($this->cookies_arr[$index]);
                }
            }
            //如果这个cookie值是空的话就不添加。为啥不写再方法首部呢，是因为上面的代码可以正好把同名的ck直接删除掉
            if (empty($temp_up_cookie->Value)) {
                return;
            }
            $this->cookies_arr[] = $temp_up_cookie;
        }
    }

    /**
     * 判断字符串是否在尾部
     * @param $str
     * @param $suffix
     * @return bool
     */
    protected static function endWith($str, $suffix) {
        $length = strlen($suffix);
        if ($length == 0) {
            return true;
        }
        return (substr($str, -$length) === $suffix);
    }

    /**
     * 判断字符串是否在首部
     * @param $str
     * @param $suffix
     * @return bool
     */
    protected static function startWith($str, $suffix) {
        $length = strlen($suffix);
        if ($length == 0) {
            return true;
        }
        return (substr($str, 0, $length) === $suffix);
    }

    /**
     * 不区分大小写判断两个字符串是否相同
     * @param $str1
     * @param $str2
     * @return bool
     */
    protected static function equal($str1, $str2) {
        return strcasecmp($str1, $str2) == 0;
    }

    /**
     * 判断url是否正确，否则抛出异常
     * @param $url
     * @return void
     * @throws Exception
     */
    public static function checkUrl($url) {
        if (!empty($url)) {
            if (filter_var($url, FILTER_VALIDATE_URL) === false) {
                throw new Exception('url校验异常');
            }

            $parse = parse_url($url);
            if (empty($parse['host'])) {
                throw new Exception('url校验异常');
            }
        }
    }
}