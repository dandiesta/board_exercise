<?php

const MAX_SECONDS = 60;
const MAX_SECONDS_PER_MINUTE = 3600;
const MAX_SECONDS_PER_HOUR = 86400;
const MAX_SECONDS_PER_DAY = 2592000;
const MAX_SECONDS_PER_MONTH = 31104000;

function getElapsedTime($date)
{
    $started = strtotime($date);
    $now = time();
    $time_elapsed = $now - $started;

    if ($time_elapsed < MAX_SECONDS) {
       $date_range = "second";
    } elseif (MAX_SECONDS <= ($time_elapsed < MAX_SECONDS_PER_MINUTE)) {
        $time_elapsed = floor($time_elapsed/MAX_SECONDS);
        $date_range = "minute";
    } elseif (MAX_SECONDS_PER_MINUTE <= ($time_elapsed < MAX_SECONDS_PER_HOUR)) {
        $time_elapsed = floor($time_elapsed/MAX_SECONDS_PER_MINUTE);
        $date_range = "hour";
    } elseif (MAX_SECONDS_PER_HOUR <= ($time_elapsed < MAX_SECONDS_PER_DAY)) {
        $time_elapsed = floor($time_elapsed/MAX_SECONDS_PER_HOUR);
        $date_range = "day";
    } elseif (MAX_SECONDS_PER_DAY <= ($time_elapsed < MAX_SECONDS_PER_MONTH)) {
        $time_elapsed = floor($time_elapsed/MAX_SECONDS_PER_DAY);
        $date_range = "month";
    } else {
        $time_elapsed = floor($time_elapsed/MAX_SECONDS_PER_MONTH);
        $date_range = "year";
    }

    echo "{$time_elapsed} " . time_label($time_elapsed, $date_range);
}

function time_label($date, $date_range)
{
    $time_label = ($date == 1) ? $date_range : $date_range . "s";
    return $time_label;
}