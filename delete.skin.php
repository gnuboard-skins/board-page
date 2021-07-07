<?php if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

/**
 * 게시글 삭제 프로세스
 * 첨부된 모든 파일을 삭제 함
 */
$sql_result = sql_query("
 select *
 from {$g5['board_file_table']}
 where `bo_table` = '{$bo_table}' and wr_id = '{$wr_id}'
");

try {
    $ac = new AttachedCloud();
    while ($row = sql_fetch_array($sql_result)) {
        $ac->delete($row['bf_fileurl']);
    }
} catch (Exception $e) {
    echo $e->getMessage();
}



