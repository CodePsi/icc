<?php


class StringUtils
{
    public static function startsWith($startsWithStr, $str) {
        if (empty($str)) return false;
        return substr($str, 0, strlen($startsWithStr)) === $startsWithStr;
    }
}