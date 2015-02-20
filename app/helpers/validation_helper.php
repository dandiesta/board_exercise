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

//checks if firstname and lastname contain letters only
function isNameValid($name)
{
    return preg_match('/^[a-z\s]*$/i', $name);
}