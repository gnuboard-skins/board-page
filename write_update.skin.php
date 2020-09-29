<?php if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

/**
 * 게시글 작성 후 뷰 페이지로 이동하지 않도록 함
 */
if($board['bo_use_category']) {
    goto_url(G5_HTTP_BBS_URL.'/board.php?bo_table='.$bo_table."&sca={$_POST['ca_name']}");
} else {
    goto_url(G5_HTTP_BBS_URL.'/board.php?bo_table='.$bo_table);
}

