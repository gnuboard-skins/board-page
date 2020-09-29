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
});
