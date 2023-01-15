<?php
/**
 * 不区分大小写判断两个字符串是否相同
 * @param $str1
 * @param $str2
 * @return bool
 */function equal($str1, $str2) {
    if($str1===null && $str2!==null)return false;
    if($str2===null && $str1!==null)return false;
    if($str2===null && $str1===null)return true;
    return strcasecmp($str1, $str2) == 0;
}