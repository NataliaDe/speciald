<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function validate_float($str)
{

    $delete_points = function ($a, $b) {

        $coma = strpos($a, '.');
        if ($coma === false) {

            return $a . '' . $b;
        } elseif ($b == '.') {
            return $a;
        }
        return $a . '' . $b;
    };


    $coma = false;
    $coma = strpos($str, ',');
    if ($coma === false) {

    } else {
        $str = str_replace(',', '.', $str);
    }

    $str = preg_replace("/[^.0-9]/", '', $str);

    $result = array_reduce(str_split($str), $delete_points, "");
    return $result;
}
