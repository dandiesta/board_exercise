<?php

const MAX_SECONDS = 60;
const MAX_SECONDS_PER_MINUTE = 3600;
const MAX_SECONDS_PER_HOUR = 86400;
const MAX_SECONDS_PER_DAY = 2592000;
const MAX_SECONDS_PER_MONTH = 31104000;

function getElapsedTime($created)
{
	$started = strtotime($created);
	$now = time();

	$time_elapsed = $now - $started;
	
	if ($time_elapsed < MAX_SECONDS) {
        $time_label = ($time_elapsed == 1) ? "second" : "seconds";
    } elseif (MAX_SECONDS <= ($time_elapsed < MAX_SECONDS_PER_MINUTE)) {
        $time_elapsed = floor($time_elapsed/MAX_SECONDS);
        $time_label = ($time_elapsed == 1) ? "minute" : "minutes";
    } elseif (MAX_SECONDS_PER_MINUTE <= ($time_elapsed < MAX_SECONDS_PER_HOUR)) {
        $time_elapsed = floor($time_elapsed/MAX_SECONDS_PER_MINUTE);
        $time_label = ($time_elapsed == 1) ? "hour" : "hours";
    } elseif (MAX_SECONDS_PER_HOUR <= ($time_elapsed < MAX_SECONDS_PER_DAY)) {
        $time_elapsed = floor($time_elapsed/MAX_SECONDS_PER_HOUR);
        $time_label = ($time_elapsed == 1) ? "day" : "days";
    } elseif (MAX_SECONDS_PER_DAY <= ($time_elapsed < MAX_SECONDS_PER_MONTH)) {
        $time_elapsed = floor($time_elapsed/MAX_SECONDS_PER_DAY);
        $time_label = ($time_elapsed == 1) ? "month" : "months";
    } else {
        $time_elapsed = floor($time_elapsed/MAX_SECONDS_PER_MONTH);
        $time_label = ($time_elapsed == 1) ? "year" : "years";
    }
   echo "{$time_elapsed} {$time_label}";
}