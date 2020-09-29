function history_nav_on(scroll) {
    const nth_h1 = $("#history th h1");
    $("#history-nav td.on").removeClass("on");
    if(scroll>($(nth_h1[4]).offset().top-150)) $("#history-nav td:nth-child(5)").addClass("on");
    else if(scroll>($(nth_h1[3]).offset().top-150)) $("#history-nav td:nth-child(4)").addClass("on");
    else if(scroll>($(nth_h1[2]).offset().top-150)) $("#history-nav td:nth-child(3)").addClass("on");
    else if(scroll>($(nth_h1[1]).offset().top-150)) $("#history-nav td:nth-child(2)").addClass("on");
    else $("#history-nav td:nth-child(1)").addClass("on");
}
$(function(){

    //*
    $("#page-contents .full-width").each(function (){
        $(this).after($("<div></div>").css('height', $(this).height()));
    });
    //*/

    $("#history-nav").each(function(){

        $("#history-nav td").each(function(index, item) {
            $(this).click(function(){
                $([document.documentElement, document.body]).animate({
                    scrollTop: $($("#history th h1")[index]).offset().top-100
                }, 500);
            });
        });

        $(window).scroll(function(event) {
            let scroll = $(window).scrollTop();
            if(scroll>400) $("#history-nav").addClass("fixed");
            else $("#history-nav").removeClass("fixed");
            history_nav_on(scroll);
        });

        history_nav_on($(window).scrollTop())
    });

    $('.accordion').accordion({
        collapsible: true,
        active: false,
        heightStyle: "content"
    });

    // 스크롤 이벤트를 통해 상단 고정 매뉴로 변화
    $(".scroll-fixed").each(function(){
        let _this = $(this);
        let fixed_top = _this.offset().top;
        $(window).scroll(function(event) {
            const scroll = $(window).scrollTop();
            if(scroll>fixed_top) _this.addClass("fixed");
            else _this.removeClass("fixed");
        });
    });

    $(window).scroll(function(){
        $("#m2s1_03 img, #m2s1_01, #m2s1_02, #m2_s2_01, #m2_s2_history .history-left, #m2_s2_history .history-right").each(function(index, item) {
            if($(this).visible(true))
                $(this).addClass("on");
        });
    });
    $("#m2s1_03 img, #m2s1_01, #m2s1_02, #m2_s2_01, #m2_s2_history .history-left, #m2_s2_history .history-right").each(function(index, item) {
        if($(this).visible(true))
            $(this).addClass("on");
    });

    // 테이블 컨텐츠의 마지막 라인 처리
    $("#page-contents table.type1 tbody").each(function(){
        let last_line = $("<tr class='last-line'><td colspan='2000'></td></tr>");
        last_line.find("td").css("width", $(this).width()+"px");
        $(this).append(last_line);
    });
});
