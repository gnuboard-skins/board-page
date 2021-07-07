<?php
include_once('../../../../../common.php');

if(!$member['mb_no']) {
    header("Content-Type: application/json");
    echo json_encode([
        'success'=>false,
        'bf_no'=>0,
        'image'=>false,
        'path'=> '',
        'msg'=> '파일업로드를 위해서는 로그인이 필요합니다.'
    ]);
    exit;
}

$table = $g5['board_file_table'];

$result = sql_query("
 select bf_source as name, bf_no, bf_filesize as size, bf_width as width, bf_height as height, bf_thumburl as thumb, bf_type
 from {$table} where
                  `bo_table` = '{$bo_table}' and
                  (
                  `wr_id` = {$wr_id} or
                  (`wr_id` = -1 and `bf_download` = {$member['mb_no']})
                  )
");

$list = [];
while ($row = sql_fetch_array($result)) {
    $row['path'] = $row['thumb'];
    $row['image'] = true;
    if($row['bf_type']==0) {
        $row['image'] = false;
        $ext = pathinfo($row['name'],PATHINFO_EXTENSION);
        $row['thumb'] = $board_skin_url.'/img/extensions/'.$ext.'.svg';
    }
    $list[] = $row;
}

header("Content-Type: application/json");
echo json_encode([
    'count'=>sizeof($list),
    'list'=>$list
]);

