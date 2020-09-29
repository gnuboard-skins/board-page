<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// ------------------------------------------------------------------
// css/js 추가
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);
add_javascript("<script src='{$board_skin_url}/script.js'></script>", 1);
add_javascript('<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous"></script>', 0);
add_javascript('<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-visible/1.2.0/jquery.visible.min.js" integrity="sha512-771ZvVCYr4EfUGXr63AcX7thw7EKa6QE1fhxi8JG7mPacB/arC0cyvYPXKUkCrX2sYKnnFCZby3ZZik42jOuSQ==" crossorigin="anonymous"></script>', 1);
add_stylesheet('<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ==" crossorigin="anonymous" />', 0);

// ------------------------------------------------------------------
// 분류 설정
// 분류를 사용하는데 분류가 없을 경우 첫번째 분류로 이동 (전체 없음)
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

// ------------------------------------------------------------------
// 마지막으로 작성한 글
$v = $list[0];

// ------------------------------------------------------------------
// 최신글 기능 {{latest, bo_table, skin, rows, subject_len}}
preg_match_all('/{{([a-z,_0-9]+)}}/', $v['wr_content'], $matchs);
foreach($matchs[1] as $idx=>$match) {
    list($func, $board_table, $skin, $rows, $subject_len) = explode(',',$match);
    if($func=='latest') {
        $latest = latest('theme/'.$skin, $board_table, $rows, $subject_len);
        $v['wr_content'] = str_replace($matchs[0][$idx], $latest, $v['wr_content']);
    }
}

// ------------------------------------------------------------------
// 분류 출력
if(isset($category_list)) {
    $id = $board['bo_3']?$board['bo_3']:'category-main';
    $li_list = '';
    foreach($category_list as $ca) {
        $li_list.= " <li> <a href='{$ca['href']}' class='{$ca['active']}'>{$ca['name']}</a> </li> ";
    }
    echo "<nav id='{$id}' class='scroll-fixed' data-offset-top='0'><div class='wrap'><ul>{$li_list}</ul></div></nav>";
}
?>

<div class="wrap">
    <?php echo $board['bo_content_head']?>
    <div id="page-contents" class="page-contents">
        <?php echo $v['wr_content']?>
    </div>
    <?php echo $board['bo_content_tail']?>

    <?php if ($write_href) { ?>
        <div class="page-contents-buttons">
            <a href="<?php echo $write_href?>" class="btn">
                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                페이지 수정
            </a>
        </div>
    <?php } ?>
</div>
