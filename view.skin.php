<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

/**
 * css/js 추가
 */
add_javascript('<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous"></script>', 0);
add_javascript('<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-visible/1.2.0/jquery.visible.min.js" integrity="sha512-771ZvVCYr4EfUGXr63AcX7thw7EKa6QE1fhxi8JG7mPacB/arC0cyvYPXKUkCrX2sYKnnFCZby3ZZik42jOuSQ==" crossorigin="anonymous"></script>', 1);
add_javascript("<script src='{$board_skin_url}/script.js'></script>", 2);
add_stylesheet('<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ==" crossorigin="anonymous" />', 0);
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 1);

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

$list_btn = <<<TAG
    <div class='page-contents-buttons right'>
        <a href="{$list_href}&sca={$view['ca_name']}" class="btn"> <i class="fa fa-reply" aria-hidden="true"></i> 수정기록 보기 종료 </a>
    </div>
TAG;

echo $list_btn;
echo $board['bo_content_head'];
echo <<<CONTENTS
<div id="page-contents" class="page-contents">
    <p id="view-title"><strong>History</strong> {$view['wr_subject']}된 페이지 입니다.</p>
    {$view['wr_content']}
</div>
CONTENTS;
echo $board['bo_content_tail'];
echo $list_btn;
