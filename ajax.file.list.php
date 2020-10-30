<?php
include_once('../../../../../common.php');

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
    $row['path'] = '/data/file/'.$bo_table.'/'.$row['bf_file'];
    if(is_file(G5_DATA_PATH.'/file/'.$bo_table.'/'.$row['bf_file'])) {
        $row['mime'] = mime_content_type(G5_DATA_PATH.'/file/'.$bo_table.'/'.$row['bf_file']);
    }
    $list[] = $row;
}

header("Content-Type: application/json");
echo json_encode([
    'count'=>sizeof($list),
    'list'=>$list
]);

