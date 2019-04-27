<?php
/*
Plugin Name: Sinon的追番列表
Plugin URI: https://sinon.top/sinon-bangumi-list/
Description: 使用短代码[bangumi]在页面上生成追番列表，在“工具”菜单中配置追番列表。
Version: 0.1
Author: Sinon
Author URI: https://sinon.top/
*/

//定义全局变量
global $saved_bangumi;

//主要功能：在短代码[bangumi]处生成追番列表
function create_bangumi_list()
{
    echo '<link rel="stylesheet" type="text/css" href="/wp-content/plugins/Sinon_Bangumi_List/css/style.css">';
    //TODO 生成追番列表
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
    $bangumi_item['url'] = $bg_json['url'];
    $bangumi_item['name_cn'] = $bg_json['name_cn'];
    $bangumi_item['name'] = $bg_json['name'];
    $bangumi_item['img'] = $bg_json['images']['large'];
    $bangumi_item['date'] = $date[0] . '年' . $date[1] . '月' . $date[2] . '日';
    $bangumi_item['count'] = $bg_json['eps_count'];
    $bangumi_item['title'] = $bg_json['summary'];
    $bangumi_item['id'] = $bg_json['id'];
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
    echo '<a href="' . $bg_json['url'] . '" target="_blank" class="bangumItem" title="' . $bg_json['summary'] . '">' .
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
    add_management_page('更新追番列表', '更新追番列表', 'edit_posts', 'sinon_bangumi_list', 'sinon_bangumi_options');
}

add_action('admin_menu', 'sinon_bangumi_adminpage');

//后台菜单总框架
function sinon_bangumi_options()
{
    if (!current_user_can('edit_posts')) {
        wp_die(__('权限不足，无法操作！'));
    }
    if ($_POST['action'] == NULL) {
        generate_option_page();
    } elseif ($_POST['action'] == 1) {
        generate_confirm_page();
    } elseif ($_POST['action'] == 2) {
        $update_flag = update_bangumi_option();
        if ($update_flag == true) {
            echo '<div id="message" class="updated fade"><p>设置已保存！</p></div>';
        } else {
            echo '<div id="message" class="updated fade"><p>设置保存失败！</p></div>';
        }
        generate_option_page();
    } elseif ($_POST['action'] == 3) {
        $update_flag = add_bangumi_item();
        if ($update_flag == true) {
            echo '<div id="message" class="updated fade"><p>番剧添加成功！</p></div>';
        } else {
            echo '<div id="message" class="updated fade"><p>番剧添加失败！</p></div>';
        }
        generate_option_page();
    }
}

//番剧修改菜单
function generate_option_page()
{
    echo "<h2>修改番剧信息</h2>";
    $saved_bangumi = get_option("sinonbangumilist_savedbangumi");
    if ($saved_bangumi == NULL) {
        echo "看来你还没有添加过番剧呢，要添加一个吗？<br>";
    } else {
        echo '<table style="line-height: 50px;COLOR: cyan;">';
        foreach ($saved_bangumi as $this_bangumi) {
            echo '<tr><td>' . $this_bangumi['name_cn'] . '</td><td>' .
                '<form action="" method="POST"><input type="hidden" name="action" value="2"><input type="hidden" name="bangumi_id" value="' . $this_bangumi['id'] . '">';
            if ($this_bangumi['status'] == 0) { //待追番
                echo  '<select name="bg_status"><option value="0" selected>待追番</option><option value=1>正在追番</option><option value=2>已追完</option></select>';
            } elseif ($this_bangumi['status'] == 1) { //正在追番
                echo  '<select name="bg_status"><option value="0">待追番</option><option value=1 selected>正在追番</option><option value=2>已追完</option></select>';
                echo  '看番进度：<input type="text" name="progress" value="' . $this_bangumi['progress'] . '"style="width: 30px;">总集数：<input type="text" name="count" value="' . $this_bangumi['count'] . '"style="width: 30px;">';
            } elseif ($this_bangumi['status'] == 2) { //已追完
                echo  '<select name="bg_status"><option value="0">待追番</option><option value=1>正在追番</option><option value=2 selected>已追完</option></select>';
            }
            echo '<input type="submit" value="修改状态" class="button button-primary"></form></td></tr>';
        }
        echo '</table><br>';
    }
    echo '<form action="" method="POST"><input type="hidden" name="action" value="1">添加番剧id：<input type="text" name="bangumi_id"><input type="submit" value="添加番剧" class="button button-primary">';
}

function generate_confirm_page()
{
    echo "<h2>确认番剧信息</h2>";
    $id = (int)$_POST['bangumi_id'];
    $add = get_bangumi_item($id);
    echo '<img src="' . $add['img'] . '"style="width:200px;height:auto;"><br><form action="" method="POST">' .
        '<input type="hidden" name="img" value="' . $add['img'] . '"><input type="hidden" name="bangumi_id" value="' . $add['id'] . '">' .
        '<input type="hidden" name="action" value="3">' .
        '番剧链接：<input type="text" name="url" value="' . $add['url'] . '"style="width:50%"><br>' .
        '中文名：<input type="text" name="name_cn" value="' . $add['name_cn'] . '"style="width:50%"><br>' .
        '日文名：<input type="text" name="name" value="' . $add['name'] . '"style="width:50%"><br>' .
        '放送日期：<input type="text" name="date" value="' . $add['date'] . '"style="width:50%"><br>' .
        '总话数：<input type="text" name="count" value="' . $add['count'] . '"style="width:50%"><br>' .
        '简介：<textarea style="width:50%;height:300px; name="title">' . $add['title'] . '</textarea><br>' .
        '<input type="submit" value="确认添加" class="button button-primary"></form>' .
        '<form action="" method="POST"><input type="hidden" name="wtf"><input type="submit" value="放弃添加" class="button action">';
}

function update_bangumi_option()
{
    $saved_bangumi = get_option("sinonbangumilist_savedbangumi");
    $id = (int)$_POST['bangumi_id'];
    $change = $saved_bangumi[$id];
    $change['status']=(int)$_POST['bg_status'];
    if ($_POST['progress'] == NULL) {
        $change['progress'] = 0;
    } else {
            $change['progress'] = $_POST['progress'];
        }
    if($_POST['count']!=NULL){
        $change['count']=$_POST['count'];
    }
    $saved_bangumi[$id] = $change;
    $flag = update_option("sinonbangumilist_savedbangumi", $saved_bangumi);
    return $flag;
}

function add_bangumi_item()
{
    $saved_bangumi = get_option("sinonbangumilist_savedbangumi");
    $id = (int)$_POST['bangumi_id'];
    $add['id'] = $id;
    $add['img'] = $_POST['img'];
    $add['url'] = $_POST['url'];
    $add['name_cn'] = $_POST['name_cn'];
    $add['name']=$_POST['name'];
    $add['date']=$_POST['date'];
    $add['count']=$_POST['count'];
    $add['title']=$_POST['title'];
    $add['status']=0;
    $add['progress']=0;
    $saved_bangumi[$id]=$add;
    $flag=update_option("sinonbangumilist_savedbangumi",$saved_bangumi);
    return $flag;
}
