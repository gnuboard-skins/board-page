<?php if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$view = sql_fetch(" SELECT * FROM {$write_table} WHERE `ca_name`='{$sca}' ORDER BY `wr_datetime` DESC LIMIT 1 ");
add_stylesheet("<link rel='stylesheet' href='{$board_skin_url}/style.css'>", 0);
add_javascript("<script src='{$board_skin_url}/ckeditor/ckeditor.js'></script>", 1);
add_javascript("<script src='{$board_skin_url}/ckfinder/ckfinder.js'></script>", 2);
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
        <div id="document-editor"><?php echo $view['wr_content']?></div>
        <div class="write-page-buttons">
            <a href="<?php echo get_pretty_url($bo_table); ?>" class="btn">취소</a>
            <button type="submit" id="btn_submit" accesskey="s" class="btn btn-submit">작성완료</button>
        </div>
    </form>
</div>

<script>
    $(function(){
        CKEDITOR.config.height = 600;
        CKEDITOR.config.width = 'auto';
        CKEDITOR.config.skin = 'office2013';
        CKEDITOR.config.extraPlugins = 'youtube';
        let editor_instance = CKEDITOR.replace( 'document-editor', {
            bodyId: 'page-contents',
            bodyClass: 'page-contents',
            contentsCss: '<?php echo $board_skin_url.'/style.css?ver='.date("YmdHis")?>',
            filebrowserBrowseUrl: '<?php echo $board_skin_url?>/ckfinder/ckfinder.html',
            filebrowserUploadUrl: '<?php echo $board_skin_url?>/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files'
        } );

        $("form#write-page").submit(function(){
            $("input[name='wr_content']").val(editor_instance.getData());
            document.getElementById("btn_submit").disabled = "disabled";
            return true;
        });
    });
</script>
