<?php

function enquote_string($string)
{
    if (!isset($string)) return;
    echo htmlspecialchars($string, ENT_QUOTES);
}

function readable_text($s)
{
    $s = htmlspecialchars($s, ENT_QUOTES);
    $s = nl2br($s);
    return $s;
}

function createPageLinks($total_rows, $current_page, $max_rows, $extra_params = null)
{
	$page_total = SimplePagination::MIN_PAGE_NUM;
	
	if ($total_rows > $max_rows){
		$page_total = ceil($total_rows / $max_rows);
	} else {
		return;
	}
	
	$page_count = SimplePagination::MIN_PAGE_NUM;
	$page_links = "";
	
	while ($page_count <= $page_total) {
		if ($page_count == $current_page) {
			$page_links .= " <a class='btn btn-default' href='?page={$page_count}&{$extra_params}' disabled>{$current_page}</a> ";
			//$page_links .= " <b>$current_page</b> ";
		} else {
			$page_links .=
			"<a class='btn btn-danger' href='?page={$page_count}&{$extra_params}'>{$page_count}</a> ";
		}
		$page_count++;
	}
	
	return $page_links;
}