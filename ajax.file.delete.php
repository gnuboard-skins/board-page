<?php
include_once('../../../../../common.php');

if(!$wr_id) {
    $wr_id = -1;
}
$bf_no = $_GET['bf_no'];

$success = false;
if($bf_no) {
    $row = sql_fetch("
 select *
 from {$g5['board_file_table']}
 where `bo_table` = '{$bo_table}'
   and wr_id = '{$wr_id}'
   and bf_no = '{$bf_no}'
LIMIT 1
");

    try {
        $ac = new AttachedCloud();
        $ac->delete($row['bf_fileurl']);
        sql_query("
 delete from {$g5['board_file_table']}
 where bo_table = '{$bo_table}'
   and wr_id = '{$wr_id}'
   and bf_no = '{$bf_no}'
");
        $success = true;
    } catch (Exception $e) {
        $success = false;
    }
}

header("Content-Type: application/json");
echo json_encode(['success'=>$success]);

