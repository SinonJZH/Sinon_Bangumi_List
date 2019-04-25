<?php
/*
Plugin Name: Sinon的追番列表
Plugin URI: https://sinon.top/
Description: Sinon的追番列表
Version: 0.1
Author: Sinon
Author URI: https://sinon.top/
*/

//主要功能：在短代码[bangumi]处生成追番列表
function create_bangumi_list()
{
    echo '<link rel="stylesheet" type="text/css" href="/wp-content/plugins/Sinon_Bangumi_List/css/style.css">';
    generate_bangumi_item(225604,2,24);
}

add_shortcode("bangumi", 'create_bangumi_list');

//使用curl进行get请求
function get($url, $timeout = 3)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout); // 设置超时
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

//获取番剧信息
function get_bangumi_item($id)
{
    $URL = "api.bgm.tv/subject/" . (string)$id . "?responseGroup=Small";
    $bangumi = get($URL);
    $bg_json = json_decode($bangumi, true);
    $date = explode('-', $bg_json['air_date']);
    $date[1] = ltrim($date[1], '0');
    $date[2] = ltrim($date[2], '0');
    //返回需要的数据
    $bangumi_item['url']=$bg_json['url'];
    $bangumi_item['img']=$bg_json['name_cn'];
    $bangumi_item['date']= $date[0] . '年' . $date[1] . '月' . $date[2] . '日<br>';
    $bangumi_item['count']=$bg_json['eps_count'];
    $bangumi_item['title']=$bg_json['summary'];
    return $bangumi_item;
}

//生成单个番剧界面
function generate_bangumi_item($id, $status, $progress = 0)
{
    $URL = "api.bgm.tv/subject/" . (string)$id . "?responseGroup=Small";
    $bangumi = get($URL);
    $bg_json = json_decode($bangumi, true);
    $date = explode('-', $bg_json['air_date']);
    $date[1] = ltrim($date[1], '0');
    $date[2] = ltrim($date[2], '0');
    echo '<a href="' . $bg_json['url'] . '" target="_blank" class="bangumItem" title="'.$bg_json['summary'].'">' .
        '<img src="' . $bg_json['images']['large'] . '">' .
        '<div class="textBox">' . $bg_json['name_cn'] .
        '<br>' .
        $bg_json['name'] . '<br>' .
        '首播日期：' . $date[0] . '年' . $date[1] . '月' . $date[2] . '日<br>';
    if ($status === 0) {
        echo '<div class="jinduBG"><div class="jinduText">进度:0/' . $bg_json['eps_count'] . '</div><div class="jinduFG" style="width:0%;"></div></div></div></a>';
    } elseif ($status === 1) {
        echo '<div class="jinduBG"><div class="jinduText">已追完</div><div class="jinduFG" style="width:100%;"></div></div></div></a>';
    } elseif ($status === 2) {
        echo '<div class="jinduBG"><div class="jinduText">进度:' . $progress . '/' . $bg_json['eps_count'] . '</div><div class="jinduFG" style="width:' . (string)((double)$progress / $bg_json['eps_count'] * 100) . '%;"></div></div></div></a>';
    }
}

//添加wordpress后台菜单
function sinon_bangumi_adminpage()
{
    add_management_page( '追番列表选项', '追番列表选项', 'edit_posts', 'sinon_bangumi_list', 'sinon_bangumi_options' );
}

add_action( 'admin_menu', 'sinon_bangumi_adminpage' );

function sinon_bangumi_options()
{
    if ( !current_user_can( 'edit_posts' ) )  {
        wp_die( __( '权限不足，无法操作！' ) );
   }

}



