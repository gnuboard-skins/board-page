Dropzone.autoDiscover = false;
$(function(){
    let editor = $("#document-editor");
    CKEDITOR.config.height = 600;
    CKEDITOR.config.width = '100%';
    CKEDITOR.config.skin = 'moono-lisa';
    CKEDITOR.config.extraPlugins = 'youtube';
    let editor_instance = CKEDITOR.replace( 'document-editor', {
        bodyId: 'page-contents',
        bodyClass: 'page-contents',
        contentsCss: [editor.data('css1'), editor.data('css2')],
    } );

    $("form#write-page").submit(function(){
        $("input[name='wr_content']").val(editor_instance.getData());
        document.getElementById("btn_submit").disabled = "disabled";
        return true;
    });

    const dz = $("#myDropzone");
    let myDropzone = new Dropzone("div#myDropzone", {
        dictDefaultMessage: "<strong><i class=\"fa fa-plus-circle\"></i> 여기에 파일을 놓거나 클릭하세요.</strong>",
        dictCancelUpload: "<i class=\"fa fa-times\" title='업로드취소'></i>",
        dictRemoveFile: "<i class=\"fa fa-trash\" title='파일삭제'></i>",
        url: dz.data("url"),
        addRemoveLinks: true,
        maxFiles: dz.data("max"),
        success: function (file, res) {
            let preview = $(file.previewElement);
            preview.attr("data-bf_no", res['bf_no']);

            let buttons = $("<div class='dz-buttons'></div>");
            if(res['image']) {
                let btn_img = $(`<button class="image">이미지</button>`);
                btn_img.click(function(){
                    editor_instance.insertHtml(`<img src="${res['path']}" alt="${file['name']}"/>`);
                    return false;
                });
                buttons.append(btn_img);
            }
            let btn_download = $(`<button class="download">다운로드</button>`);
            btn_download.click(function(){
                editor_instance.insertHtml(`<div class="file-download">
<img src="${res['thumb']}" alt="thumb"/>
<p>
<a href="${res['download']}" target="_blank">${res['name']}</a>
<span>${res['size']}bytes</span>
</p>
<a href="${res['download']}" target="_blank" class="icon">&nbsp;</a>
</div>`);
                return false;
            });
            buttons.append(btn_download);
            preview.find(".dz-details").append(buttons);
            if(res['thumb']) this.emit("thumbnail", file, res['thumb']);
        },
        init: function() {

            let myDropzone = this;

            $.ajax({
                method:"post",
                url: dz.data("url-init"),
                success: function(data){
                    if(data['count']>0) {
                        data['list'].forEach(function(el){
                            const mockFile = {
                                name: el['name'],
                                size: el['size'],
                                type: el['mime'],
                                accepted: true            // required if using 'MaxFiles' option
                            };
                            const res = {'path':el['path'], 'bf_no':el['bf_no'], 'image':el['image'], 'download': el['download']};
                            myDropzone.emit("addedfile", mockFile);
                            if(el['thumb']) {
                                myDropzone.emit("thumbnail", mockFile, el['thumb']);
                            } else {
                                myDropzone.emit("thumbnail", mockFile, el['path']);
                            }
                            myDropzone.emit("success", mockFile, el);
                            myDropzone.emit("complete", mockFile);
                            myDropzone.files.push(mockFile);    // add to files array
                        });
                    }
                }
            });
        },
        removedfile: function (file) {
            const bf_no = $(file.previewElement).data("bf_no");
            if(confirm("파일을 삭제하면 복구가 불가능합니다.\n정말로 파일을 삭제하시겠습니까?\n")) {
                $.ajax({
                    method:"post",
                    url: dz.data("url-remove")+"&bf_no="+bf_no,
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
});
