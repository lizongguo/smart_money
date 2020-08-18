<?php

function str_truncate($string, $length = 80, $etc = '...', $break_words = false, $middle = false) {
    $string = preg_replace('/(\s*|&nbsp[;]?)/ui', '',strip_tags($string));
    if ($length == 0)
        return '';

    // $string has utf-8 encoding
    if (iconv_strlen($string,"utf-8") > $length) {
        $length -= min($length, iconv_strlen($etc,"utf-8"));
        if (!$break_words && !$middle) {
            //$string = preg_replace('/\s+?(\S+)?$/u', '', mb_substr($string, 0, $length + 1));
        }
        if (!$middle) {
            return iconv_substr($string, 0, $length,"utf-8") . $etc;
        } else {
            return iconv_substr($string, 0, $length / 2,"utf-8") . $etc . iconv_substr($string, - $length / 2,"utf-8");
        }
    } else {
        return $string;
    }
}