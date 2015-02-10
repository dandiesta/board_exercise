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
    public $current_page;        // 現在のページ番号
    public $prev;           // ひとつ前のページ番号
    public $next;           // ひとつ次のページ番号
    public $is_last_page;   // 最終ページかどうか

    public function __construct($current_page)
    {
        $this->current_page = $current_page;
        $this->prev = max($current_page - 1, 0);
        $this->next = $current_page + 1;
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
        $this->is_last_page = ($item <= $this->current_page) ? true : false;
    }

    public function threadLinks($chunk_thread, $i)
    {
        $chunks = $chunk_thread[$i-1];

        foreach ($chunks as $chunk) {
            $title = $chunk->title;
            $id = $chunk->id;
            $user_id = $chunk->user_id;
            $username = $chunk->username;
            $created = $chunk->created;
            $usertype = $chunk->usertype;

            $per_chunk[] = array(
                'title'    => $title,
                'id'       => $id, 
                'user_id'  => $user_id,
                'username' => $username,
                'created'  => $created,
                'usertype' => $usertype
            );
        }

        return $per_chunk;
    }

    public function topThreadLinks($chunk_thread, $i)
    {
        $chunks = $chunk_thread[$i-1];

        foreach ($chunks as $chunk) {
            $title = $chunk->title;
            $id = $chunk->id;
            $user_id = $chunk->user_id;
            $username = $chunk->username;
            $created = $chunk->created;
            $usertype = $chunk->usertype;
            $thread_count = $chunk->thread_count;

            $per_chunk[] = array(
                'title'    => $title,
                'id'       => $id, 
                'user_id'  => $user_id,
                'username' => $username,
                'created'  => $created,
                'usertype' => $usertype,
                'thread_count' => $thread_count
            );
        }

        return $per_chunk;
    }

    // public function commentLinks($chunk_comment, $i)
    // {
    //     $chunks = $chunk_comment[$i-1];

    //     foreach ($chunks as $chunk) {
    //         $id = $chunk->id; 
    //         $body = $chunk->body;
    //         $created = $chunk->created;
    //         $username = $chunk->username;
    //         $user_id = $chunk->user_id;
    //         //$usertype = $chunk->usertype;

    //         $per_chunk[] = array(
    //             'id'       => $id,
    //             'user_id'  => $user_id,
    //             'body'     => $body,
    //             'created'  => $created, 
    //             'username' => $username
    //           //  'usertype' => $usertype
    //         );
    //     }

    //     return $per_chunk;
    // }

    public function commentLinks($chunk_comment, $i)
    {
        $chunks = $chunk_comment[$i-1];

        foreach ($chunks as $chunk) {
            $id = $chunk->id; 
            $body = $chunk->body;
            $created = $chunk->created;
            $username = $chunk->username;
            $user_id = $chunk->user_id;
            $liked = $chunk->liked;
            $disliked = $chunk->disliked;
            //$usertype = $chunk->usertype;

            $per_chunk[] = array(
                'id'       => $id,
                'user_id'  => $user_id,
                'body'     => $body,
                'created'  => $created, 
                'username' => $username,
                'liked'    => $liked,
                'disliked' => $disliked
            );
        }

        return $per_chunk;
    }
}