<?php
namespace Mondo\AppBundle\Util;

class Util {
    public static function randStr($str, $n) {
        $ret = '';
        for($i=0; $i<$n; $i++) $ret .= $str[rand(0,strlen($str)-1)];
        return $ret;
    }

    public static function randStrAlpha($n) {
        return self::randStr('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', $n);
    }
}

