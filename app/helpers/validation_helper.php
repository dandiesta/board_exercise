<?php

function validate_between($check, $min, $max)
{
    //mb_strlen gets the string length
    $n = mb_strlen($check);

    return $min <= $n && $n <= $max;
}

function redirect($url)
{
    header("Location: " . $url);
}