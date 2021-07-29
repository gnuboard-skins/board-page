<?php include_once('../../../../../common.php');

$response = [
    'success'=>false,
    'bf_no'=>0,
    'image'=>false,
    'path'=> '',
    'msg'=>'파일업로드를 위해서는 로그인이 필요합니다.'
];

if(!$member['mb_no']) {
    header("Content-Type: application/json");
    echo json_encode($response);
    exit;
}
$response['msg'] = '';

try {
    $ac = new AttachedCloud();
    $file = $ac->upload();
    if($file['name']) {
        $bf_download = 0;
        if(!$wr_id) {
            $wr_id = -1;
            $bf_download = $member['mb_no'];
        }

        if($file['image-size'][2]==0) {
            $ext = pathinfo($file['name'],PATHINFO_EXTENSION);
            $file['thumb'] = $board_skin_url.'/img/extensions/'.$ext.'.svg';
        }

        $row = sql_fetch("
 select max(bf_no) as max_bf_no
 from {$g5['board_file_table']}
 where bo_table = '{$bo_table}' and wr_id = {$wr_id}
");
        $bf_no = (int)$row['max_bf_no']+1;

        $sql = " insert into {$g5['board_file_table']}
            set bo_table = '{$bo_table}',
                 wr_id = {$wr_id},
                 bf_no = '{$bf_no}',
                 bf_source = '{$file['name']}',
                 bf_file = '{$file['name']}',
                 bf_content = '',
                 bf_fileurl = '{$file['download']}',
                 bf_thumburl = '{$file['thumb']}',
                 bf_storage = '',
                 bf_download = '{$bf_download}',
                 bf_filesize = '".(int)$file['file-size']."',
                 bf_width = '".(int)$file['image-size'][0]."',
                 bf_height = '".(int)$file['image-size'][1]."',
                 bf_type = '".(int)$file['image-size'][2]."',
                 bf_datetime = '".G5_TIME_YMDHIS."' ";
        sql_query($sql);
        $response['success'] = true;
        $response['bf_no'] = $bf_no;
        $response['path'] = $file['src'];
        $response['thumb'] = $file['thumb'];
        $response['width'] = $file['image-size'][0];
        $response['height'] = $file['image-size'][1];
        $response['size'] = $file['file-size'];
        if(strpos($file['contentType'],'image') !== false) {
            $response['image'] = true;
        }
    }
} catch (Exception $e) {
    $response['msg'] = $e->getMessage();
}

header("Content-Type: application/json");
echo json_encode($response);
