<?php if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$view = sql_fetch(" SELECT * FROM {$write_table} WHERE `ca_name`='{$sca}' ORDER BY `wr_datetime` DESC LIMIT 1 ");
add_javascript("<script src='{$board_skin_url}/ckeditor/ckeditor.js'></script>", 1);
add_javascript('<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.2/min/dropzone.min.js" integrity="sha512-9WciDs0XP20sojTJ9E7mChDXy6pcO0qHpwbEJID1YVavz2H6QBz5eLoDD8lseZOb2yGT8xDNIV7HIe1ZbuiDWg==" crossorigin="anonymous"></script>', 2);
add_stylesheet('<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.2/dropzone.min.css" integrity="sha512-3g+prZHHfmnvE1HBLwUnVuunaPOob7dpksI7/v6UnF/rnKGwHf/GdEq9K7iEN7qTtW+S0iivTcGpeTBqqB04wA==" crossorigin="anonymous" />', 0);
add_stylesheet("<link rel='stylesheet' href='{$board_skin_url}/style.css'>", 1);
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
        <div id="myDropzone" class="dropzone"></div>
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
        } );

        $("form#write-page").submit(function(){
            $("input[name='wr_content']").val(editor_instance.getData());
            document.getElementById("btn_submit").disabled = "disabled";
            return true;
        });
        Dropzone.options.myDropzone = {
            dictDefaultMessage: "<strong>여기에 파일을 놓거나 클릭하세요.</strong>",
            dictCancelUpload: "업로드 취소",
            dictRemoveFile: "<a href='#'>파일삭제</a>",
            url: "<?php echo "{$board_skin_url}/ajax.file.upload.php?bo_table={$bo_table}&sca={$sca}"?>",
            addRemoveLinks: true,
            success: function (file, res) {
                $(file.previewElement).attr("data-bf_no", res['bf_no']);
                $(file.previewElement).find(".dz-details").click(function(){
                    editor_instance.insertHtml(`<img src="${res['path']}" alt="${file['name']}"/>`);
                });
            },
            init: function() {

                let myDropzone = this;

                $.ajax({
                    method:"post",
                    url: "<?php echo "{$board_skin_url}/ajax.file.list.php?bo_table={$bo_table}&sca={$sca}"?>",
                    success: function(data){
                        if(data['count']>0) {
                            data['list'].forEach(function(el){
                                const mockFile = {
                                    name: el['name'],
                                    size: el['size'],
                                    type: el['mime'],
                                    accepted: true            // required if using 'MaxFiles' option
                                };
                                const res = {'path':el['path'], 'bf_no':el['bf_no']};
                                myDropzone.emit("addedfile", mockFile);
                                myDropzone.emit("thumbnail", mockFile, el['path']);
                                myDropzone.emit("success", mockFile, res);
                                myDropzone.emit("complete", mockFile);
                                myDropzone.files.push(mockFile);    // add to files array
                            });
                        }
                    }
                });
            },
            removedfile: function (file) {
                const bf_no = $(file.previewElement).data("bf_no");
                $.ajax({
                    method:"post",
                    url: "<?php echo "{$board_skin_url}/ajax.file.delete.php?bo_table={$bo_table}&sca={$sca}"?>&bf_no="+bf_no,
                    success: function(data){
                        if(data['success']) {
                            alert("파일삭제성공");
                            $(file.previewElement).remove();
                        }
                    }
                });
            }
        }
    });
</script>
