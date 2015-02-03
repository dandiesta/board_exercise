<?php
/**
 * 単純なページネーションを扱うクラス
 *
 * - ページ番号は 1 以上
 * - リンクには前のページと次のページしか使わない
 *
 * @package SimplePagination
 * @author  tatsuya.tsuruoka@gmail.com
 * @url     https://github.com/ttsuruoka/php-simple-pagination
 */
class SimplePagination
{
    public $current;        // 現在のページ番号
    public $prev;           // ひとつ前のページ番号
    public $next;           // ひとつ次のページ番号
    public $is_last_page;   // 最終ページかどうか

    public function __construct($current)
    {
        $this->current = $current;
        $this->prev = max($current - 1, 0);
        $this->next = $current + 1;
    }

    /**
     * 最終ページかどうか判定する
     *
     * このメソッドの前に、アイテムを1ページに表示する件数 + 1 個取得しておく。
     * 取得できたアイテムの数が1ページに表示する件数以下だったとき、
     * 現在のページが最終ページであることが分かる。
     *
     * 最終ページではなかったときは余分に取得したアイテムを破棄する。
     *
     * @param array &$items 取得できたアイテムの数から最終ページかどうか判断する。
     * @return void
     */
    public function checkLastPage($item)
    {
        if ($item <= $this->current) {
            $this->is_last_page = true;
        } else {
            $this->is_last_page = false;
        }
    }

    public function threadLinks($chunk_thread, $i)
    {
        $chunk = $chunk_thread[$i-1];

        foreach ($chunk as $c) {
           $title = $c->title;
           $id = $c->id;
           $user_id = $c->user_id;
           $username = $c->username;
           $created = $c->created;

            $individual[] = array(
                'title'    => $title,
                'id'       => $id, 
                'user_id'  => $user_id,
                'username' => $username,
                'created'  => $created
            );
        }

        return $individual;
    }

    public function commentLinks($chunk_comment, $i)
    {
        $chunk = $chunk_comment[$i-1];

        foreach ($chunk as $c) {
            $id = $c->id; 
            $body = $c->body;
            $created = $c->created;
            $username = $c->username;
            $user_id = $c->user_id;

            $individual[] = array(
                'body'     => $body,
                'created'  => $created, 
                'username' => $username,
                'user_id'  => $user_id,
                'id'       => $id
            );
        }

        return $individual;
    }
}