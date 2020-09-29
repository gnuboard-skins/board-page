<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

/**
 * css/js 추가
 */
add_javascript('<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous"></script>', 0);
add_javascript('<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-visible/1.2.0/jquery.visible.min.js" integrity="sha512-771ZvVCYr4EfUGXr63AcX7thw7EKa6QE1fhxi8JG7mPacB/arC0cyvYPXKUkCrX2sYKnnFCZby3ZZik42jOuSQ==" crossorigin="anonymous"></script>', 1);
add_javascript("<script src='{$board_skin_url}/script.js'></script>", 2);
add_stylesheet('<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ==" crossorigin="anonymous" />', 0);
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 1);

/**
 * 분류 설정
 * 분류를 사용하는데 분류가 없을 경우 첫번째 분류로 이동 (전체 없음)
 */
if($board['bo_use_category']) {

    $ca_list = explode('|',$board['bo_category_list']);

    if(!$sca || $sca=='') {
        $sca = $ca_list[0];
        goto_url(G5_HTTP_BBS_URL.'/board.php?bo_table='.$bo_table."&sca={$sca}");
        exit;
    }

    $category_list = [];
    foreach($ca_list as $ca) {
        $category_list[] = [
            'href'=>get_pretty_url($bo_table,'','sca='.urlencode($ca)),
            'active'=>($ca==$sca)?'on':'',
            'name'=>$ca
        ];
    }
}

/**
 * 마지막으로 작성한 글
 */
$v = $list[0];

/**
 * 최신글 기능 {{latest, bo_table, skin, rows, subject_len}}
 */
preg_match_all('/{{([a-z,_0-9]+)}}/', $v['wr_content'], $matchs);
foreach($matchs[1] as $idx=>$match) {
    list($func, $board_table, $skin, $rows, $subject_len) = explode(',',$match);
    if($func=='latest') {
        $latest = latest('theme/'.$skin, $board_table, $rows, $subject_len);
        $v['wr_content'] = str_replace($matchs[0][$idx], $latest, $v['wr_content']);
    }
}

/**
 * 분류 출력
 */
if(isset($category_list)) {
    $li_list = '';
    foreach($category_list as $ca) {
        $li_list.= " <li> <a href='{$ca['href']}' class='{$ca['active']}'>{$ca['name']}</a> </li> ";
    }
    echo "<nav class='page-category scroll-fixed full-width' data-offset-top='0'><div class='wrap'><ul>{$li_list}</ul></div></nav>";
}

/**
 * 페이지 수정기록 리스트
 */
$history_list = '';
foreach($list as $idx => $article) {
    if($idx==0) continue;
    $history_list.= " <li> <a href='{$article['href']}'>{$article['wr_subject']}</a> </li> ";
}
$history_list = <<<HISTORY
<div class="dim-layer">
    <div class="dimBg"></div>
    <div id="page-history" class="pop-layer">
        <div class="pop-container">
            <div class="pop-conts">
                <!--content //-->
                <h2>아래 링크를 클릭하면 해당 버전의 기록을 볼 수 있습니다.</h2>
                <ul>{$history_list}</ul>

                <div class="btn-r">
                    <a href="#" class="btn-layerClose">닫기</a>
                </div>
                <!--// content-->
            </div>
        </div>
    </div>
</div>
HISTORY;

/**
 * 페이지 수정 버튼
 */
$write_btn = '';
if ($write_href) {
    $write_btn = <<<TAG
    <div class='page-contents-buttons right'>
        <a href="#page-history" class="btn layer-popup"> <i class="fa fa-history" aria-hidden="true"></i> 버전관리 </a>
        <a href="{$write_href}&sca={$sca}" class="btn"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i> 페이지 수정 </a>
    </div>
TAG;
}

/**
 * 컨텐츠 출력
 */
echo $write_btn;
echo $board['bo_content_head'];
echo <<<CONTENTS
<div id="page-contents" class="page-contents">{$v['wr_content']}</div>
CONTENTS;
echo $board['bo_content_tail'];
echo $write_btn;

echo $history_list;