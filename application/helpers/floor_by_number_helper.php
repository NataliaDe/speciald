<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function get_text_by_floor($n)
{

    switch ($n) {
        case 1:$text = 'одно';
            break;
        case 2:$text = 'двух';
            break;
        case 3:$text = 'трех';
            break;

        case 4:$text = 'четырех';
            break;
        case 5:$text = 'пяти';
            break;

        case 6:$text = 'шести';
            break;
        case 7:$text = 'семи';
            break;
        case 8:$text = 'восьми';
            break;
        case 9:$text = 'девяти';
            break;
        case 10:$text = 'десяти';
            break;
        case 11:$text = 'одиннадцати';
            break;
        case 12:$text = 'двенадцати';
            break;
        case 13:$text = 'тренадцати';
            break;
        case 14:$text = 'четырнадцати';
            break;
        case 15:$text = 'пятнадцати';
            break;
        case 16:$text = 'шестнадцати';
            break;
        case 17:$text = 'семнадцати';
            break;
        case 18:$text = 'восемнадцати';
            break;
        case 19:$text = 'девятнадцати';
            break;
        case 20:$text = 'двадцати';
            break;
        case 21:$text = 'двадцатиодно';
            break;
        case 22:$text = 'двадцатидвух';
            break;
        case 23:$text = 'двадцатитрех';
            break;
        case 24:$text = 'двадцатичетырех';
            break;
        case 25:$text = 'двадцатипяти';
            break;


        default: $text = '';
            break;
    }

    if (!empty($text)) {
        $text = $text . 'этажный';
    }

    return $text;
}
