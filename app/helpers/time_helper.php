<?php

function getElapsedTime($created)
{
	$started = strtotime($created);
	$now = time();

	$time_elapsed = $now - $started;
	
	if ($time_elapsed < 60) {
            echo " $time_elapsed seconds";
        } elseif (60 <= ($time_elapsed < 3600)) {
            $minute = floor($time_elapsed/60);
            echo " $minute minutes";
        } elseif (3600 <= ($time_elapsed < 86400)) {
            $hour = floor($time_elapsed/3600);
            echo " $hour hours";
        } elseif (86400 <= ($time_elapsed < 2592000)) {
            $day = floor($time_elapsed/86400);
            echo " $day days";
        } elseif (2592000 <= ($time_elapsed < 31104000)) {
            $month = floor($time_elapsed/2592000);
            echo " $month months";
        } else {
            $year = floor($time_elapsed/31104000);
            echo " $year years";
        }
}