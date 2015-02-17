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
            $page_links .= "<a class='btn btn-default' href='?page={$page_count}&{$extra_params}' disabled>{$current_page}</a> ";
        } else {
            $page_links .=
            "<a class='btn btn-danger' href='?page={$page_count}&{$extra_params}'>{$page_count}</a> ";
        }
        $page_count++;
    }
    
    return $page_links;
}

function Pagination($data, $limit = null, $current = null, $adjacents = null)
{
    $result = array();

    if (isset($data, $limit) === true)
    {
        $result = range(1, ceil($data / $limit));

        if (isset($current, $adjacents) === true)
        {
            if (($adjacents = floor($adjacents / 2) * 2 + 1) >= 1)
            {
                $result = array_slice($result, max(0, min(count($result) - $adjacents, intval($current) - ceil($adjacents / 2))), $adjacents);
            }
        }
    }

    return $result;
}