function layer_popup(el){

    const $el = $(el);		//레이어의 id를 $el 변수에 저장
    const isDim = $el.prev().hasClass('dimBg');	//dimmed 레이어를 감지하기 위한 boolean 변수

    isDim ? $('.dim-layer').fadeIn() : $el.fadeIn();

    const $elWidth = ~~($el.outerWidth()),
        $elHeight = ~~($el.outerHeight()),
        docWidth = $(document).width(),
        docHeight = $(document).height();

    // 화면의 중앙에 레이어를 띄운다.
    if ($elHeight < docHeight || $elWidth < docWidth) {
        $el.css({
            marginTop: -$elHeight /2,
            marginLeft: -$elWidth/2
        })
    } else {
        $el.css({top: 0, left: 0});
    }

    $el.find('a.btn-layerClose').click(function(){
        isDim ? $('.dim-layer').fadeOut() : $el.fadeOut(); // 닫기 버튼을 클릭하면 레이어가 닫힌다.
        return false;
    });

    $('.layer .dimBg').click(function(){
        $('.dim-layer').fadeOut();
        return false;
    });
}

$(function(){

    /**
     * 전체너비 섹션 컨텐츠 일때에 아래 컨텐츠가 밀려 올라가는 버그 수정
     */
    $(".full-width").each(function (){
        $(this).after($("<div></div>").css('height', $(this).height()));
    });

    /**
     * jquery-ui 아코디언 적용
     */
    $('.accordion').accordion({
        collapsible: true,
        active: false,
        heightStyle: "content"
    });

    /**
     * 스크롤 이벤트를 통해 상단 고정 매뉴로 변화
     */
    $(".scroll-fixed").each(function(){
        let _this = $(this);
        _this.css("width", _this.width()+"px");
        let fixed_top = _this.offset().top;
        $(window).scroll(function(event) {
            const scroll = $(window).scrollTop();
            if(scroll>fixed_top) _this.addClass("fixed");
            else _this.removeClass("fixed");
        });
    });

    /**
     * 테이블 컨텐츠의 마지막 라인 처리
     * - 셀합치기 등을 하였을 경우 합쳐진 셀의 마지막 라인은 스타일 적용이 되지 않음
     */
    $(".page-contents table.type1 tbody").each(function(){
        let last_line = $("<tr class='last-line'><td colspan='2000'></td></tr>");
        last_line.find("td").css("width", $(this).width()+"px");
        $(this).append(last_line);
    });

    /**
     * 레이어팝업
     */
    $('.layer-popup').click(function(){
        const $href = $(this).attr('href');
        layer_popup($href);
    });

    /**
     * 페이지 복구
     */
    $(".restore").click(function(){
        if(confirm("정말로 이 버전의 페이지로 복구하시겠습니까?")) {
            location.href=$(this).attr("href");
        }
        return false;
    });
});
