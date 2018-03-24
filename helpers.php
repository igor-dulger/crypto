<?php
/**
 * Created by PhpStorm.
 * User: Igor
 * Date: 26.02.2018
 * Time: 22:08
 */
function xorWithAddition($a, $b)
{
    if (strlen($a) > strlen($b)) {
        $b = str_pad($b, strlen($a), '0');
    } else {
        $a = str_pad($a, strlen($b), '0');
    }
    return bin2hex(hex2bin($a) ^ hex2bin($b));
}

function xorWithCut($a, $b)
{
    if (strlen($a) > strlen($b)) {
        $a = substr($a, 0, strlen($b));
    } else {
        $b = substr($b, 0, strlen($a));
    }
    //print_r([$a, $b]);
    return bin2hex(hex2bin($a) ^ hex2bin($b));
}

function getPrintable($hexline)
{
    $result = '';
    for ($i=0; $i<strlen($hexline); $i+=2) {
        $char = hex2bin($hexline[$i].$hexline[$i+1]);
        if ((ord($char) >= 32 && ord($char) <= 122) || ord($char) == 0) {
            $result .= (ord($char)) ? $char : ' ';
        } else {
            $result .= "~";
        }
    }
    return $result;
}
