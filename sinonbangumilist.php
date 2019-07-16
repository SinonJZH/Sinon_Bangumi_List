<?php
/*
Plugin Name: Sinon的追番列表
Plugin URI: https://sinon.top/sinon-bangumi-list/
Description: 使用短代码[bangumi]在页面上生成追番列表，在“工具”菜单中配置追番列表。
Version: 1.1.4
Author: Sinon
Author URI: https://sinon.top/
*/


//主要功能：在短代码[bangumi]处生成追番列表
function Sinon_BL_create_bangumi_list()
{
    $css_url = esc_url(plugins_url('css/style.css', __FILE__));
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
        echo '<a href="' . esc_url($saved_bangumi[$id]['url']) . '" target="_blank" class="bangumItem" title="' .
            esc_attr($saved_bangumi[$id]['title']) . '">' .
            '<img src="' . esc_url($saved_bangumi[$id]['img']) . '">' .
            '<div class="textBox">' . esc_attr($saved_bangumi[$id]['name_cn']) .
            '<br>' . esc_attr($saved_bangumi[$id]['name']) . '<br>' .
            '首播日期：' . esc_attr($saved_bangumi[$id]['date']) . '<br>' .
            '<div class="jinduBG"><div class="jinduText">进度:' . esc_attr($saved_bangumi[$id]['progress']) .
            '/' . esc_attr($saved_bangumi[$id]['count']) .
            '</div><div class="jinduFG" style="width:' .
            esc_attr((string) ((float) $saved_bangumi[$id]['progress'] / $saved_bangumi[$id]['count'] * 100)) .
            '%;"></div></div></div></a>';
    }
    if ($status_1 % 2) {
        echo '<a target="_blank" class="bangumItem"  style="box-shadow: none;"></a>';
    }
    echo '<h2>追完番剧(' . $status_2 . ')</h2>';
    for ($i = 0; $i < $status_2; $i++) {
        $id = $index[2][$i];
        echo '<a href="' . esc_url($saved_bangumi[$id]['url']) . '" target="_blank" class="bangumItem" title="' . esc_attr($saved_bangumi[$id]['title']) . '">' .
            '<img src="' . esc_url($saved_bangumi[$id]['img']) . '">' .
            '<div class="textBox">' . esc_attr($saved_bangumi[$id]['name_cn']) .
            '<br>' . esc_attr($saved_bangumi[$id]['name']) . '<br>' .
            '首播日期：' . esc_attr($saved_bangumi[$id]['date']) . '<br>' .
            '<div class="jinduBG"><div class="jinduText">已追完</div><div class="jinduFG" style="width:100%;"></div></div></div></a>';
    }
    if ($status_2 % 2) {
        echo '<a target="_blank" class="bangumItem"  style="box-shadow: none;"></a>';
    }
    echo '<h2>待追番剧(' . $status_0 . ')</h2>';
    for ($i = 0; $i < $status_0; $i++) {
        $id = $index[0][$i];
        echo '<a href="' . esc_url($saved_bangumi[$id]['url']) . '" target="_blank" class="bangumItem" title="' . esc_attr($saved_bangumi[$id]['title']) . '">' .
            '<img src="' . esc_url($saved_bangumi[$id]['img']) . '">' .
            '<div class="textBox">' . esc_attr($saved_bangumi[$id]['name_cn']) .
            '<br>' . esc_attr($saved_bangumi[$id]['name']) . '<br>' .
            '首播日期：' . esc_attr($saved_bangumi[$id]['date']) . '<br>' .
            '<div class="jinduBG"><div class="jinduText">进度:0/' . esc_attr($saved_bangumi[$id]['count']) .
            '</div><div class="jinduFG" style="width:0%;"></div></div></div></a>';
    }
}

add_shortcode("bangumi", 'Sinon_BL_create_bangumi_list');

//获取番剧信息
function Sinon_BL_get_bangumi_item($id)
{
    $URL = "http://api.bgm.tv/subject/" . (string) $id . "?responseGroup=Small";
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
function Sinon_BL_generate_bangumi_item($id, $status, $progress = 0)
{
    $URL = "api.bgm.tv/subject/" . (string) $id . "?responseGroup=Small";
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
        echo '<div class="jinduBG"><div class="jinduText">进度:0/' . $bg_json['eps_count'] .
            '</div><div class="jinduFG" style="width:0%;"></div></div></div></a>';
    } elseif ($status === 2) {
        echo '<div class="jinduBG"><div class="jinduText">已追完</div><div class="jinduFG" style="width:100%;"></div></div></div></a>';
    } elseif ($status === 1) {
        echo '<div class="jinduBG"><div class="jinduText">进度:' . $progress . '/' . $bg_json['eps_count'] .
            '</div><div class="jinduFG" style="width:' .
            (string) ((float) $progress / $bg_json['eps_count'] * 100) . '%;"></div></div></div></a>';
    }
}

//添加wordpress后台菜单
function Sinon_BL_sinon_bangumi_adminpage()
{
    add_management_page('更新追番列表', '更新追番列表', 'edit_posts', 'sinon_bangumi_list', 'Sinon_BL_sinon_bangumi_options');
}

add_action('admin_menu', 'Sinon_BL_sinon_bangumi_adminpage');

//后台菜单总框架
function Sinon_BL_sinon_bangumi_options()
{
    if (!current_user_can('edit_posts')) {
        wp_die(__('权限不足，无法操作！'));
    }
    if ($_POST['action'] == NULL) { //默认界面
        Sinon_BL_generate_bangumi_option_page();
    } elseif ($_POST['action'] == 1 && wp_verify_nonce($_POST['nonce'], "Sinon_Bangumi_Action_1")) { //添加番剧（确认）
        Sinon_BL_generate_confirm_page();
    } elseif ($_POST['action'] == 2 && wp_verify_nonce($_POST['nonce'], "Sinon_Bangumi_Action_2")) { //更新番剧状态
        $update_flag = Sinon_BL_update_bangumi_option();
        if ($update_flag == true) {
            echo '<div id="message" class="notice inline notice-success  is-dismissible"><p>设置已保存！</p></div>';
        } else {
            echo '<div id="message" class="notice inline notice-error  is-dismissible"><p>设置保存失败！</p></div>';
        }
        Sinon_BL_generate_bangumi_option_page();
    } elseif ($_POST['action'] == 3 && wp_verify_nonce($_POST['nonce'], "Sinon_Bangumi_Action_3")) { //添加番剧
        $update_flag = Sinon_BL_add_bangumi_item();
        if ($update_flag == true) {
            echo '<div id="message" class="notice inline notice-success  is-dismissible"><p>番剧添加成功！</p></div>';
        } else {
            echo '<div id="message" class="notice inline notice-error  is-dismissible"><p>番剧添加失败！</p></div>';
        }
        Sinon_BL_generate_bangumi_option_page();
    } elseif ($_POST['action'] == 4 && wp_verify_nonce($_POST['nonce'], "Sinon_Bangumi_Action_4")) { //删除指定番剧
        $update_flag = Sinon_BL_del_certain_bangunmi();
        if ($update_flag == true) {
            echo '<div id="message" class="notice inline notice-success  is-dismissible"><p>番剧删除成功！</p></div>';
        } else {
            echo '<div id="message" class="notice inline notice-error  is-dismissible"><p>番剧删除失败！</p></div>';
        }
        Sinon_BL_generate_bangumi_option_page();
    } elseif ($_POST['action'] == 5 && wp_verify_nonce($_POST['nonce'], "Sinon_Bangumi_Action_5")) { //删除全部番剧
        $update_flag = Sinon_BL_del_all_bangunmi();
        if ($update_flag == true) {
            echo '<div id="message" class="notice inline notice-success  is-dismissible"><p>番剧删除成功！</p></div>';
        } else {
            echo '<div id="message" class="notice inline notice-error  is-dismissible"><p>番剧删除失败！</p></div>';
        }
        Sinon_BL_generate_bangumi_option_page();
    } elseif ($_POST['action'] == 6 && wp_verify_nonce($_POST['nonce'], "Sinon_Bangumi_Action_6")) { //删除番剧（确认）
        Sinon_BL_generate_del_confirm_page();
    } else {
        echo '<div id="message" class="notice inline notice-error  is-dismissible"><p>抱歉，当前操作无法被验证，请重试！</p></div>';
        Sinon_BL_generate_bangumi_option_page();
    }
}

//番剧修改菜单
function Sinon_BL_generate_bangumi_option_page()
{
    echo "<h2>修改番剧信息</h2>";
    $saved_bangumi = get_option("sinonbangumilist_savedbangumi");
    $add_nonce = wp_create_nonce('Sinon_Bangumi_Action_1');
    $change_nonce = wp_create_nonce('Sinon_Bangumi_Action_2');
    $delete_nonce = wp_create_nonce('Sinon_Bangumi_Action_6');
    if ($saved_bangumi == NULL) {
        echo "看来你还没有添加过番剧呢，要添加一个吗？<br>";
    } else {
        echo '<table style="line-height: 50px;text-align: center;">' .
            '<tr><td style="width:300px">番剧名称</td><td style="width:400px">番剧状态</td><td style="width:auto">删除按钮</td>';
        foreach ($saved_bangumi as $this_bangumi) {
            echo '<tr><td>' . esc_attr($this_bangumi['name_cn']) . '</td><td>' .
                '<form action="" method="POST"><input type="hidden" name="action" value="2">
                <input type="hidden" name="nonce" value="' . esc_attr($change_nonce) . '">
                <input type="hidden" name="bangumi_id" value="' . esc_attr($this_bangumi['id']) . '">';
            if ($this_bangumi['status'] == 0) { //待追番
                echo  '<select name="bg_status"><option value="0" selected>待追番</option>
                <option value=1>正在追番</option><option value=2>已追完</option></select>';
            } elseif ($this_bangumi['status'] == 1) { //正在追番
                echo  '<select name="bg_status"><option value="0">待追番</option><option value=1 selected>正在追番</option>
                <option value=2>已追完</option></select>';
                echo  '看番进度：<input type="text" name="progress" value="' . esc_attr($this_bangumi['progress']) .
                    '"style="width: 30px;">总集数：<input type="text" name="count" value="' . esc_attr($this_bangumi['count']) .
                    '"style="width: 30px;">';
            } elseif ($this_bangumi['status'] == 2) { //已追完
                echo  '<select name="bg_status"><option value="0">待追番</option><option value=1>正在追番</option><option value=2 selected>已追完</option></select>';
            }
            echo '<input type="submit" value="修改状态" class="button button-primary" style="vertical-align:middle;"></form>';
            if ($this_bangumi['status'] == 1) {
                echo '<form action="" method="POST"><input type="hidden" name="action" value="2">
                <input type="hidden" name="nonce" value="' . esc_attr($change_nonce) . '">
                <input type="hidden" name="bangumi_id" value="' . esc_attr($this_bangumi['id']) . '">';
                if ($this_bangumi['progress'] < $this_bangumi['count']) {
                    echo '<input type="hidden" name="progress" value="' . esc_attr($this_bangumi['progress'] + 1) . '">
                <input type="submit" value="进度+1" class="button button-primary"></form>';
                } else {
                    echo '<input type="hidden" name="bg_status" value="2">
                    <input type="submit" value="设置为已追完" class="button button-primary"></form>';
                }
            }
            echo '</td>' . '<td><form action="" method="POST"><input type="hidden" name="action" value="6">
                <input type="hidden" name="bangumi_id" value="' . esc_attr($this_bangumi['id']) .
                '"><input type="hidden" name="nonce" value="' . $delete_nonce . '">
                <input type="submit" value="删除" class="button button-primary" style="color:red;vertical-align:middle;"></form></td></tr>';
        }
        echo '</table><br>';
    }
    echo '<form action="" method="POST"><input type="hidden" name="action" value="1">
    添加番剧id：<input type="text" name="bangumi_id">
    <input type="hidden" name="nonce" value="' . $add_nonce . '">
    <input type="submit" value="添加番剧" class="button button-primary"></form><br>';
    echo '<form action="" method="POST"><input type="hidden" name="action" value="6">
    <input type="hidden" name="bangumi_id" value="all">
    <input type="hidden" name="nonce" value="' . $delete_nonce . '">
    <input type="submit" value="删除全部番剧" class="button button-primary" style="color:red;"></form>';
}

function Sinon_BL_generate_confirm_page()
{
    echo "<h2>确认番剧信息</h2>";
    if (preg_match_all('/^[1-9][0-9]*$/', $_POST['bangumi_id']) == 0) {
        echo '<div id="message" class="notice inline notice-error  is-dismissible"><p>错误！非法的番剧id！</p></div>';
        Sinon_BL_generate_bangumi_option_page();
        return NULL;
    }
    $id = (int) $_POST['bangumi_id'];
    $add = Sinon_BL_get_bangumi_item($id);
    $add_nonce = wp_create_nonce('Sinon_Bangumi_Action_3');
    echo '<img src="' . esc_url($add['img']) . '"style="width:200px;height:auto;"><br><form action="" method="POST">' .
        '<input type="hidden" name="img" value="' . esc_url($add['img']) . '"><input type="hidden" name="bangumi_id" value="' . esc_attr($add['id']) . '">' .
        '<input type="hidden" name="action" value="3">' .
        '<input type="hidden" name="nonce" value="' . $add_nonce . '">' .
        '番剧链接：<input type="text" name="url" value="' . esc_url($add['url']) . '"style="width:50%"><br>' .
        '中文名：<input type="text" name="name_cn" value="' . esc_attr($add['name_cn']) . '"style="width:50%"><br>' .
        '日文名：<input type="text" name="name" value="' . esc_attr($add['name']) . '"style="width:50%"><br>' .
        '放送日期：<input type="text" name="date" value="' . esc_attr($add['date']) . '"style="width:50%"><br>' .
        '总话数：<input type="text" name="count" value="' . esc_attr($add['count']) . '"style="width:50%"><br>' .
        '简介：<textarea style="width:50%;height:300px;" name="title">' . esc_attr($add['title']) . '</textarea><br>' .
        '<input type="submit" value="确认添加" class="button button-primary"></form>' .
        '<form action="" method="POST"><input type="hidden" name="wtf"><input type="submit" value="放弃添加" class="button action">';
}

function Sinon_BL_generate_del_confirm_page()
{
    echo "<h2>删除番剧确认</h2>";
    $del_once_nonce = wp_create_nonce('Sinon_Bangumi_Action_4');
    $del_all_nonce = wp_create_nonce('Sinon_Bangumi_Action_5');
    if ($_POST['bangumi_id'] == 'all') {
        echo '<p style="font-size:30px;color:red">警告！你即将删除所有番剧！该操作不可逆！</p>' .
            '<form action="" method="POST"><input type="hidden" name="action" value="5">
            <input type="hidden" name="nonce" value="' . $del_all_nonce . '">
            <input type="submit" value="确认删除" class="button button-primary" style="color:red;"></form>' .
            '<form action="" method="POST"><input type="hidden" name="wtf"><input type="submit" value="放弃删除" class="button action">';
    } else {
        if (preg_match_all('/^[1-9][0-9]*$/', $_POST['bangumi_id']) == 0) {
            echo '<div id="message" class="notice inline notice-error  is-dismissible"><p>错误！非法的番剧id！</p></div>';
            return false;
        }
        $saved_bangumi = get_option("sinonbangumilist_savedbangumi");
        $id = (int) sanitize_text_field($_POST['bangumi_id']);
        if ($saved_bangumi[$id] == NULL) {
            echo '<div id="message" class="updated fade"><p>番剧不存在！</p></div>';
            Sinon_BL_generate_bangumi_option_page();
        } else {
            $name = $saved_bangumi[$id]['name_cn'];
            echo '你即将要删除的番剧为:' . esc_attr($name) . '，确认删除吗？<br>' .
                '<form action="" method="POST"><input type="hidden" name="action" value="4">
                <input type="hidden" name="bangumi_id" value="' . esc_attr($_POST['bangumi_id']) . '">
                <input type="hidden" name="nonce" value="' . $del_once_nonce . '">
                <input type="submit" value="确认删除" class="button button-primary" style="color:red;"></form>' .
                '<form action="" method="POST"><input type="hidden" name="wtf"><input type="submit" value="放弃删除" class="button action">';
        }
    }
}

//更新番剧信息
function Sinon_BL_update_bangumi_option()
{
    $saved_bangumi = get_option("sinonbangumilist_savedbangumi");
    if (preg_match_all('/^[1-9][0-9]*$/', $_POST['bangumi_id']) == 0) {
        echo '<div id="message" class="notice inline notice-error  is-dismissible"><p>错误！非法的番剧id！</p></div>';
        return false;
    }
    $id = (int) $_POST['bangumi_id'];
    $change = $saved_bangumi[$id];
    if ($_POST['bg_status'] != NULL) {
        if (preg_match_all('/^[0-9]*$/', $_POST['bg_status']) == 0) {
            echo '<div id="message" class="notice inline notice-error  is-dismissible"><p>错误！非法的进度id！</p></div>';
            return false;
        }
        $change['status'] = (int) sanitize_text_field($_POST['bg_status']);
    }
    if ($_POST['progress'] == NULL) {
        $change['progress'] = 0;
    } else {
        if (preg_match_all('/^[0-9]*$/', $_POST['progress']) == 0) {
            echo '<div id="message" class="notice inline notice-error  is-dismissible"><p>错误！非法的进度！</p></div>';
            return false;
        }
        $change['progress'] = sanitize_text_field($_POST['progress']);
    }
    if ($_POST['count'] != NULL) {
        if (preg_match_all('/^[0-9]*$/', $_POST['count']) == 0) {
            echo '<div id="message" class="notice inline notice-error  is-dismissible"><p>错误！非法的总集数！</p></div>';
            return false;
        }
        $change['count'] = sanitize_text_field($_POST['count']);
    }
    $saved_bangumi[$id] = $change;
    uasort($saved_bangumi,'Sinon_BL_sort_cmp');
    $flag = update_option("sinonbangumilist_savedbangumi", $saved_bangumi);
    return $flag;
}

//添加新的番剧
function Sinon_BL_add_bangumi_item()
{
    if (preg_match_all('/^[1-9][0-9]*$/', $_POST['bangumi_id']) == 0) {
        echo '<div id="message" class="notice inline notice-error  is-dismissible"><p>错误！非法的番剧id！</p></div>';
        return false;
    }
    if (preg_match_all('/^[0-9]*$/', $_POST['count']) == 0) {
        echo '<div id="message" class="notice inline notice-error  is-dismissible"><p>错误！非法的总集数！</p></div>';
        return false;
    }
    if (preg_match_all('/^[0-9][0-9][0-9][0-9]年[0-1]?[0-9]月[0-3]?[0-9]日$/', $_POST['date']) == 0) {
        echo '<div id="message" class="notice inline notice-error  is-dismissible"><p>错误！非法的首播日期！</p></div>';
        return false;
    }
    $saved_bangumi = get_option("sinonbangumilist_savedbangumi");
    $id = (int) $_POST['bangumi_id'];
    $add['id'] = $id;
    $add['img'] = esc_url_raw($_POST['img']);
    $add['url'] = esc_url_raw($_POST['url']);
    $add['name_cn'] = sanitize_text_field($_POST['name_cn']);
    $add['name'] = sanitize_text_field($_POST['name']);
    $add['date'] = sanitize_text_field($_POST['date']);
    $add['count'] = sanitize_text_field($_POST['count']);
    $add['title'] = sanitize_text_field($_POST['title']);
    $add['status'] = 0;
    $add['progress'] = 0;
    $saved_bangumi[$id] = $add;
    uasort($saved_bangumi,'Sinon_BL_sort_cmp');
    $flag = update_option("sinonbangumilist_savedbangumi", $saved_bangumi);
    return $flag;
}

//删除所有番剧(删除数据库选项)
function Sinon_BL_del_all_bangunmi()
{
    $flag = delete_option("sinonbangumilist_savedbangumi");
    return $flag;
}

//删除单个番剧
function Sinon_BL_del_certain_bangunmi()
{
    $saved_bangumi = get_option("sinonbangumilist_savedbangumi");
    $id = (int) $_POST['bangumi_id'];
    unset($saved_bangumi[$id]);
    uasort($saved_bangumi,'Sinon_BL_sort_cmp');
    $flag = update_option("sinonbangumilist_savedbangumi", $saved_bangumi);
    return $flag;
}

//番剧排序比较函数
function Sinon_BL_sort_cmp($a, $b)
{
    if($a['status']==1 && $b['status']==1){
        return $a['progress'] < $b['progress'];
    }
    if ($a['status'] == 1) $c = 1;
    elseif ($a['status'] == 0) $c = 2;
    else $c = 3;
    if ($b['status'] == 1) $d = 1;
    elseif ($b['status'] == 0) $d = 2;
    else $d = 3;
    return $c > $d;
}
