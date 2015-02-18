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
function nameChecker($name)
{
    return preg_match('/^[a-z\s]*$/i', $name);
}

//checks if password is same as password for confirmation
function passwordChecker($password, $confirm_password)
{
    return ($password == $confirm_password);
}