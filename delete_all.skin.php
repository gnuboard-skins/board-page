<?php if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

/**
 * 선택된 게시글을 삭제할 때
 * 첨부된 모든 파일들을 삭제 함
 */
try {
    $ac = new AttachedCloud();
    foreach ($tmp_array as $wr_id) {
        $sql_result = sql_query(" select * from {$g5['board_file_table']} where `bo_table` = '{$bo_table}' and wr_id = '{$wr_id}' ");
        while ($row = sql_fetch_array($sql_result)) {
            $ac->delete($row['bf_fileurl']);
        }
    }
} catch (Exception $e) {
    echo $e->getMessage();
}



