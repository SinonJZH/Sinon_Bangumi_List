<?php
/*
Plugin Name: Sinon的追番列表
Plugin URI: https://sinon.top/sinon-bangumi-list/
Description: 使用短代码[bangumi]在页面上生成追番列表，在“工具”菜单中配置追番列表。
Version: 1.1.2
Author: Sinon
Author URI: https://sinon.top/
*/


//主要功能：在短代码[bangumi]处生成追番列表
function create_bangumi_list()
{
    $css_url = plugins_url("/Sinon_Bangumi_List/css/style.css");
    wp_enqueue_style('Sinon_Bangumi_Item', $css_url);
    $saved_bangumi = get_option("sinonbangumilist_savedbangumi");
    $status_0 = 0;
    $status_1 = 0;
    $status_2 = 0;
    foreach ($saved_bangumi as $a) {
        if ($a['status'] == 0) {
            $index[0][$status_0++] = $a['id'];
        } elseif ($a['status'] == 1) {
            $index[1][$status_1++] = $a['id'];
        } elseif ($a['status'] == 2) {
            $index[2][$status_2++] = $a['id'];
        }
    }
    echo '<h2>正在追番(' . $status_1 . ')</h2>';
    for ($i = 0; $i < $status_1; $i++) {
        $id = $index[1][$i];
        echo '<a href="' . $saved_bangumi[$id]['url'] . '" target="_blank" class="bangumItem" title="' . $saved_bangumi[$id]['title'] . '">' .
            '<img src="' . $saved_bangumi[$id]['img'] . '">' .
            '<div class="textBox">' . $saved_bangumi[$id]['name_cn'] .
            '<br>' . $saved_bangumi[$id]['name'] . '<br>' .
            '首播日期：' . $saved_bangumi[$id]['date'] . '<br>' .
            '<div class="jinduBG"><div class="jinduText">进度:' . $saved_bangumi[$id]['progress'] . '/' . $saved_bangumi[$id]['count'] . '</div><div class="jinduFG" style="width:' . (string)((double)$saved_bangumi[$id]['progress'] / $saved_bangumi[$id]['count'] * 100) . '%;"></div></div></div></a>';
    }
    if ($status_1 % 2) {
        echo '<a target="_blank" class="bangumItem"  style="box-shadow: none;"></a>';
    }
    echo '<h2>追完番剧(' . $status_2 . ')</h2>';
    for ($i = 0; $i < $status_2; $i++) {
        $id = $index[2][$i];
        echo '<a href="' . $saved_bangumi[$id]['url'] . '" target="_blank" class="bangumItem" title="' . $saved_bangumi[$id]['title'] . '">' .
            '<img src="' . $saved_bangumi[$id]['img'] . '">' .
            '<div class="textBox">' . $saved_bangumi[$id]['name_cn'] .
            '<br>' . $saved_bangumi[$id]['name'] . '<br>' .
            '首播日期：' . $saved_bangumi[$id]['date'] . '<br>' .
            '<div class="jinduBG"><div class="jinduText">已追完</div><div class="jinduFG" style="width:100%;"></div></div></div></a>';
    }
    if ($status_2 % 2) {
        echo '<a target="_blank" class="bangumItem"  style="box-shadow: none;"></a>';
    }
    echo '<h2>待追番剧(' . $status_0 . ')</h2>';
    for ($i = 0; $i < $status_0; $i++) {
        $id = $index[0][$i];
        echo '<a href="' . $saved_bangumi[$id]['url'] . '" target="_blank" class="bangumItem" title="' . $saved_bangumi[$id]['title'] . '">' .
            '<img src="' . $saved_bangumi[$id]['img'] . '">' .
            '<div class="textBox">' . $saved_bangumi[$id]['name_cn'] .
            '<br>' . $saved_bangumi[$id]['name'] . '<br>' .
            '首播日期：' . $saved_bangumi[$id]['date'] . '<br>' .
            '<div class="jinduBG"><div class="jinduText">进度:0/' . $saved_bangumi[$id]['count'] . '</div><div class="jinduFG" style="width:0%;"></div></div></div></a>';
    }
}

add_shortcode("bangumi", 'create_bangumi_list');

//获取番剧信息
function get_bangumi_item($id)
{
    $URL = "http://api.bgm.tv/subject/" . (string)$id . "?responseGroup=Small";
    $request = wp_remote_get($URL);
    $response = wp_remote_retrieve_body($request);;
    $bg_json = json_decode($response, true);
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
    } elseif ($status === 2) {
        echo '<div class="jinduBG"><div class="jinduText">已追完</div><div class="jinduFG" style="width:100%;"></div></div></div></a>';
    } elseif ($status === 1) {
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
        generate_bangumi_option_page();
    } elseif ($_POST['action'] == 1) {
        generate_confirm_page();
    } elseif ($_POST['action'] == 2) {
        $update_flag = update_bangumi_option();
        if ($update_flag == true) {
            echo '<div id="message" class="updated fade"><p>设置已保存！</p></div>';
        } else {
            echo '<div id="message" class="updated fade"><p>设置保存失败！</p></div>';
        }
        generate_bangumi_option_page();
    } elseif ($_POST['action'] == 3) {
        $update_flag = add_bangumi_item();
        if ($update_flag == true) {
            echo '<div id="message" class="updated fade"><p>番剧添加成功！</p></div>';
        } else {
            echo '<div id="message" class="updated fade"><p>番剧添加失败！</p></div>';
        }
        generate_bangumi_option_page();
    } elseif ($_POST['action'] == 4) {
        $update_flag = del_certain_bangunmi();
        if ($update_flag == true) {
            echo '<div id="message" class="updated fade"><p>番剧删除成功！</p></div>';
        } else {
            echo '<div id="message" class="updated fade"><p>番剧删除失败！</p></div>';
        }
        generate_bangumi_option_page();
    } elseif ($_POST['action'] == 5) {
        $update_flag = del_all_bangunmi();
        if ($update_flag == true) {
            echo '<div id="message" class="updated fade"><p>番剧删除成功！</p></div>';
        } else {
            echo '<div id="message" class="updated fade"><p>番剧删除失败！</p></div>';
        }
        generate_bangumi_option_page();
    } elseif ($_POST['action'] == 6) {
        generate_del_confirm_page();
    }
}

//番剧修改菜单
function generate_bangumi_option_page()
{
    echo "<h2>修改番剧信息</h2>";
    $saved_bangumi = get_option("sinonbangumilist_savedbangumi");
    if ($saved_bangumi == NULL) {
        echo "看来你还没有添加过番剧呢，要添加一个吗？<br>";
    } else {
        echo '<table style="line-height: 50px;">' .
            '<tr><td>番剧名称</td><td>番剧状态</td><td>删除按钮</td>';
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
            echo '<input type="submit" value="修改状态" class="button button-primary"></form></td>' .
                '<td><form action="" method="POST"><input type="hidden" name="action" value="6"><input type="hidden" name="bangumi_id" value="' . $this_bangumi['id'] . '"><input type="submit" value="删除" class="button button-primary" style="color:red;"></form></td></tr>';
        }
        echo '</table><br>';
    }
    echo '<form action="" method="POST"><input type="hidden" name="action" value="1">添加番剧id：<input type="text" name="bangumi_id"><input type="submit" value="添加番剧" class="button button-primary"></form><br>';
    echo '<form action="" method="POST"><input type="hidden" name="action" value="6"><input type="hidden" name="bangumi_id" value="all"><input type="submit" value="删除全部番剧" class="button button-primary" style="color:red;"></form>';
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
        '简介：<textarea style="width:50%;height:300px;" name="title">' . $add['title'] . '</textarea><br>' .
        '<input type="submit" value="确认添加" class="button button-primary"></form>' .
        '<form action="" method="POST"><input type="hidden" name="wtf"><input type="submit" value="放弃添加" class="button action">';
}

function generate_del_confirm_page()
{
    echo "<h2>删除番剧确认</h2>";
    if ($_POST['bangumi_id'] == 'all') {
        echo '<p style="font-size:30px;color:red">警告！你即将删除所有番剧！该操作不可逆！</p>' .
            '<form action="" method="POST"><input type="hidden" name="action" value="5"><input type="submit" value="确认删除" class="button button-primary" style="color:red;"></form>' .
            '<form action="" method="POST"><input type="hidden" name="wtf"><input type="submit" value="放弃删除" class="button action">';
    } else {
        $saved_bangumi = get_option("sinonbangumilist_savedbangumi");
        $id = (int)$_POST['bangumi_id'];
        if ($saved_bangumi[$id] == NULL) {
            echo '<div id="message" class="updated fade"><p>番剧不存在！</p></div>';
            generate_bangumi_option_page();
        } else {
            $name = $saved_bangumi[$id]['name_cn'];
            echo '你即将要删除的番剧为:' . $name . '，确认删除吗？<br>' .
                '<form action="" method="POST"><input type="hidden" name="action" value="4"><input type="hidden" name="bangumi_id" value="' . $_POST['bangumi_id'] . '">' .
                '<input type="submit" value="确认删除" class="button button-primary" style="color:red;"></form>' .
                '<form action="" method="POST"><input type="hidden" name="wtf"><input type="submit" value="放弃删除" class="button action">';
        }
    }
}

//更新番剧信息
function update_bangumi_option()
{
    $saved_bangumi = get_option("sinonbangumilist_savedbangumi");
    $id = (int)$_POST['bangumi_id'];
    $change = $saved_bangumi[$id];
    $change['status'] = (int)$_POST['bg_status'];
    if ($_POST['progress'] == NULL) {
        $change['progress'] = 0;
    } else {
        $change['progress'] = $_POST['progress'];
    }
    if ($_POST['count'] != NULL) {
        $change['count'] = $_POST['count'];
    }
    $saved_bangumi[$id] = $change;
    $flag = update_option("sinonbangumilist_savedbangumi", $saved_bangumi);
    return $flag;
}

//添加新的番剧
function add_bangumi_item()
{
    $saved_bangumi = get_option("sinonbangumilist_savedbangumi");
    $id = (int)$_POST['bangumi_id'];
    $add['id'] = $id;
    $add['img'] = $_POST['img'];
    $add['url'] = $_POST['url'];
    $add['name_cn'] = $_POST['name_cn'];
    $add['name'] = $_POST['name'];
    $add['date'] = $_POST['date'];
    $add['count'] = $_POST['count'];
    $add['title'] = $_POST['title'];
    $add['status'] = 0;
    $add['progress'] = 0;
    $saved_bangumi[$id] = $add;
    $flag = update_option("sinonbangumilist_savedbangumi", $saved_bangumi);
    return $flag;
}

//删除所有番剧(删除数据库选项)
function del_all_bangunmi()
{
    $flag = delete_option("sinonbangumilist_savedbangumi");
    return $flag;
}

//删除单个番剧
function del_certain_bangunmi()
{
    $saved_bangumi = get_option("sinonbangumilist_savedbangumi");
    $id = (int)$_POST['bangumi_id'];
    unset($saved_bangumi[$id]);
    $flag = update_option("sinonbangumilist_savedbangumi", $saved_bangumi);
    return $flag;
}
