<?php if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$view = sql_fetch(" SELECT * FROM {$write_table} WHERE `ca_name`='{$sca}' ORDER BY `wr_datetime` DESC LIMIT 1 ");
add_javascript("<script src='{$board_skin_url}/ckeditor4/ckeditor.js'></script>", 1);
add_javascript("<script src='{$board_skin_url}/dropzone-5.7.0/dist/min/dropzone.min.js'></script>", 2);
add_javascript("<script src='{$board_skin_url}/write.js'></script>", 20);

add_stylesheet("<link rel='stylesheet' href='{$board_skin_url}/dropzone-5.7.0/dist/min/dropzone.min.css'/>", 0);
add_stylesheet("<link rel='stylesheet' href='{$board_skin_url}/style.css'>", 1);

/**
 * 게시판 여분필드 뽑아오기
 */
$bo = [];
for($idx=1; $idx<=10; $idx++) {
    $key = 'bo_'.$idx.'_subj';
    if($board[$key]!='') $bo[$board[$key]] = $board['bo_'.$idx];
}

$upload_count = $board['bo_upload_count'];
?>
<div class="wrap">
    <form name="fwrite" id="write-page" action="<?php echo $action_url ?>" method="post" autocomplete="off">
        <input type="hidden" name="uid" value="<?php echo get_uniqid(); ?>">
        <input type="hidden" name="w" value="<?php echo $w ?>">
        <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
        <input type="hidden" name="wr_id" value="<?php echo $wr_id ?>">
        <input type="hidden" name="wr_subject" value="<?php echo date("Y-m-d H:i:s")?> 작성">
        <input type="hidden" name="wr_content" value="">
        <?php if ($is_category) { ?>
            <input type="hidden" name="ca_name" value="<?php echo $sca?>">
        <?php } ?>
        <div id="document-editor"
             data-css1="<?php echo $board_skin_url.'/style.css?ver='.date("YmdHis")?>"
             data-css2="<?php echo $bo['custom-css']?$bo['custom-css'].'?ver='.date("YmdHis"):''?>"
        ><?php echo $view['wr_content']?></div>
        <div id="myDropzone" class="dropzone"
             data-max="<?php echo $upload_count?>"
             data-url="<?php echo "{$board_skin_url}/ajax.file.upload.php?bo_table={$bo_table}&sca={$sca}&wr_id={$wr_id}"?>"
             data-url-init="<?php echo "{$board_skin_url}/ajax.file.list.php?bo_table={$bo_table}&sca={$sca}&wr_id={$wr_id}"?>"
             data-url-remove="<?php echo "{$board_skin_url}/ajax.file.delete.php?bo_table={$bo_table}&sca={$sca}&wr_id={$wr_id}"?>"
             data-download-icon="<?php echo "{$board_skin_url}/img/download.png"?>"
        ></div>
        <p>※ 파일을 클릭 하면 에디터에 삽입됩니다. </p>
        <div class="write-page-buttons">
            <a href="<?php echo get_pretty_url($bo_table); ?>" class="btn">취소</a>
            <button type="submit" id="btn_submit" accesskey="s" class="btn btn-submit">작성완료</button>
        </div>
    </form>
</div>
