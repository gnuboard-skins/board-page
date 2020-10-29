<?php
include_once('../../../../../common.php');

$bo_table = $_GET['bo_table'];

// 디렉토리가 생성
@mkdir(G5_DATA_PATH.'/file/'.$bo_table, G5_DIR_PERMISSION);
@chmod(G5_DATA_PATH.'/file/'.$bo_table, G5_DIR_PERMISSION);

$chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));

$upload = [
    'file'  => '',
    'source'=> '',
    'filesize'=>'',
    'image' => [
        0=>0,
        1=>0,
        2=>0
    ],
    'fileurl'=>'',
    'thumburl'=>'',
    'storage'=>'',
    'del_check'=>false
];
$tmp_file  = $_FILES['file']['tmp_name'];
$filesize  = $_FILES['file']['size'];
$filename  = $_FILES['file']['name'];
$filename  = get_safe_filename($filename);
$dest_file = null;

if (is_uploaded_file($tmp_file)) {

    //=================================================================\
    // 090714
    // 이미지나 플래시 파일에 악성코드를 심어 업로드 하는 경우를 방지
    // 에러메세지는 출력하지 않는다.
    //-----------------------------------------------------------------
    $timg = @getimagesize($tmp_file);
    // image type
    if (
        preg_match("/\.({$config['cf_image_extension']})$/i", $filename) ||
        preg_match("/\.({$config['cf_flash_extension']})$/i", $filename)
    ) {
        if ($timg['2'] < 1 || $timg['2'] > 16) return;
    }
    //=================================================================

    $upload['image'] = $timg;

    // 프로그램 원래 파일명
    $upload['source'] = $filename;
    $upload['filesize'] = $filesize;

    // 아래의 문자열이 들어간 파일은 -x 를 붙여서 웹경로를 알더라도 실행을 하지 못하도록 함
    $filename = preg_replace("/\.(php|pht|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $filename);

    shuffle($chars_array);
    $shuffle = implode('', $chars_array);

    // 첨부파일 첨부시 첨부파일명에 공백이 포함되어 있으면 일부 PC에서 보이지 않거나 다운로드 되지 않는 현상이 있습니다. (길상여의 님 090925)
    $upload['file'] = abs(ip2long($_SERVER['REMOTE_ADDR'])).'_'.substr($shuffle,0,8).'_'.replace_filename($filename);

    $dest_file = G5_DATA_PATH.'/file/'.$bo_table.'/'.$upload['file'];

    // 업로드가 안된다면 에러메세지 출력하고 죽어버립니다.
    $error_code = move_uploaded_file($tmp_file, $dest_file) or die($_FILES['bf_file']['error']);

    // 올라간 파일의 퍼미션을 변경합니다.
    chmod($dest_file, G5_FILE_PERMISSION);
}

if (!get_magic_quotes_gpc()) {
    $upload['source'] = addslashes($upload['source']);
}

$row = sql_fetch("
 select max(bf_no) as max_bf_no
 from {$g5['board_file_table']}
 where bo_table = '{$bo_table}' and wr_id = '-1'
");
$bf_no = (int)$row['max_bf_no']+1;

$sql = " insert into {$g5['board_file_table']}
            set bo_table = '{$bo_table}',
                 wr_id = '-1',
                 bf_no = '{$bf_no}',
                 bf_source = '{$upload['source']}',
                 bf_file = '{$upload['file']}',
                 bf_content = '',
                 bf_fileurl = '{$upload['fileurl']}',
                 bf_thumburl = '{$upload['thumburl']}',
                 bf_storage = '{$upload['storage']}',
                 bf_download = 0,
                 bf_filesize = '".(int)$upload['filesize']."',
                 bf_width = '".(int)$upload['image'][0]."',
                 bf_height = '".(int)$upload['image'][1]."',
                 bf_type = '".(int)$upload['image'][2]."',
                 bf_datetime = '".G5_TIME_YMDHIS."' ";
sql_query($sql);

header("Content-Type: application/json");
echo json_encode([
    'path'=> '/data/file/'.$bo_table.'/'.$upload['file']
]);