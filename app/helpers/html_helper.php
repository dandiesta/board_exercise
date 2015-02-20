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

function smiley($string)
{    
    $my_smilies = array(
        ':)'       => '<img src="/bootstrap/img/smiley/smile.png" alt="smile" height="30px" width="30px"/>',
        ':-)'      => '<img src="/bootstrap/img/smiley/smile.png" alt="smile" height="30px" width="30px"/>',
        ':D'       => '<img src="/bootstrap/img/smiley/laugh.png" alt="laugh" height="30px" width="30px"/>',
        ':-D'      => '<img src="/bootstrap/img/smiley/laugh.png" alt="laugh" height="30px" width="30px"/>',
        ':('       => '<img src="/bootstrap/img/smiley/sad.png" alt="sad" height="30px" width="30px"/>',
        ':-('      => '<img src="/bootstrap/img/smiley/sad.png" alt="sad" height="30px" width="30px"/>',
        '>:|'      => '<img src="/bootstrap/img/smiley/pissed.png" alt="pissed" height="30px" width="30px"/>',
        ':o'       => '<img src="/bootstrap/img/smiley/shocked.png" alt="shocked" height="30px" width="30px"/>',
        ':O'       => '<img src="/bootstrap/img/smiley/shocked.png" alt="shocked" height="30px" width="30px"/>',
        ':P'       => '<img src="/bootstrap/img/smiley/tongue.png" alt="tongue" height="30px" width="30px"/>',
        ':-P'      => '<img src="/bootstrap/img/smiley/tongue.png" alt="tongue" height="30px" width="30px"/>',
        ';)'       => '<img src="/bootstrap/img/smiley/wink.png" alt="wink" height="30px" width="30px"/>',
        ':/'       => '<img src="/bootstrap/img/smiley/disappointed.png" alt="disappointed" height="30px" width="30px"/>',
        ':*'       => '<img src="/bootstrap/img/smiley/kiss.png" alt="kiss" height="30px" width="30px"/>',
        ':?'       => '<img src="/bootstrap/img/smiley/think.png" alt="think" height="30px" width="30px"/>',
        'X('       => '<img src="/bootstrap/img/smiley/sorry.png" alt="sorry" height="30px" width="30px"/>',
        '@_@'      => '<img src="/bootstrap/img/smiley/dizzy.png" alt="dizzy" height="30px" width="30px"/>',
        'B)'       => '<img src="/bootstrap/img/smiley/cool.png" alt="cool" height="30px" width="30px"/>',
        '<3'       => '<img src="/bootstrap/img/smiley/heart.png" alt="heart" height="30px" width="30px"/>',
        '(y)'      => '<img src="/bootstrap/img/smiley/like.png" alt="like" height="30px" width="30px"/>',
        '(n)'      => '<img src="/bootstrap/img/smiley/no.svg" alt="no" height="30px" width="30px"/>',
        '(ezra)'   => '<img src="/bootstrap/img/smiley/ezra.jpg" alt="ezra" height="30px" width="30px"/>',
        '(angry)'  => '<img src="/bootstrap/img/smiley/angry.png" alt="angry" height="30px" width="30px"/>',
        '(inlove)' => '<img src="/bootstrap/img/smiley/inlove.png" alt="inlove" height="30px" width="30px"/>',
        '(evil)'   => '<img src="/bootstrap/img/smiley/evil.png" alt="evil" height="30px" width="30px"/>',
        '(shy)'    => '<img src="/bootstrap/img/smiley/shy.png" alt="shy" height="40px" width="40px"/>',
    );
 
    return str_replace(array_keys($my_smilies), array_values($my_smilies), $string);
}