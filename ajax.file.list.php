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

$wr_id = -1;
if($sca) {
    foreach(explode('|',$board['bo_category_list']) as $idx=>$ca) {
        if($sca==$ca) $wr_id = $idx;
    }
}

$table = $g5['board_file_table'];

$result = sql_query("
select bf_source as name, bf_file, bf_no, bf_filesize as size, bf_width as width, bf_height as height
from {$table} where `bo_table` = '{$bo_table}' and `wr_id` = {$wr_id}
");

$list = [];
while ($row = sql_fetch_array($result)) {
    $row['path'] = $row['thumb'];
    $row['size'] = number_format($row['size']);
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

