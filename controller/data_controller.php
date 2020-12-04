<?php

namespace Sinon_Bangumi_List;

class data_controller
{
    //生成番剧索引
    public static function bangumi_index($saved_bangumi)
    {
        if ($saved_bangumi == null)
            return null;
        $index_status = get_option('sinonbangumilist_index_status', false);
        if ($index_status)
            return  get_option('sinonbangumilist_index');
        $status_0 = 0;
        $status_1 = 0;
        $status_2 = 0;
        $index = array(array(), array(), array());
        foreach ($saved_bangumi as $a) {
            if ($a['status'] == 0) {
                $index[0][$status_0++] = $a['id'];
            } elseif ($a['status'] == 1) {
                $index[1][$status_1++] = $a['id'];
            } elseif ($a['status'] == 2) {
                $index[2][$status_2++] = $a['id'];
            }
        }
        update_option('sinonbangumilist_index_status', true);
        update_option('sinonbangumilist_index', $index);
        return $index;
    }

    //更新追番进度
    public static function update_bangumi_option()
    {
        $saved_bangumi = get_option("sinonbangumilist_savedbangumi");
        if (preg_match_all('/^[1-9][0-9]*$/', $_POST['bangumi_id']) == 0) {
            helpers::show_message('错误！非法的番剧id！', 'error');
            return false;
        }
        $id = (int) $_POST['bangumi_id'];
        $change = $saved_bangumi[$id];
        if (key_exists('bg_status', $_POST)) {
            if (preg_match_all('/^[0-9]*$/', $_POST['bg_status']) == 0) {
                helpers::show_message('错误！非法的进度状态！', 'error');
                return false;
            }
            $change['status'] = (int) sanitize_text_field($_POST['bg_status']);
        }
        if (!key_exists('progress', $_POST)) {
            $change['progress'] = 0;
        } else {
            if (preg_match_all('/^[0-9]*.*[0-9]*$/', $_POST['progress']) == 0) {
                helpers::show_message('错误！非法的进度！', 'error');
                return false;
            }
            $change['progress'] = sanitize_text_field($_POST['progress']);
        }
        if (key_exists('count', $_POST)) {
            if (preg_match_all('/^[0-9]*$/', $_POST['count']) == 0) {
                helpers::show_message('错误！非法的总集数！', 'error');
                return false;
            }
            $change['count'] = sanitize_text_field($_POST['count']);
        }
        if (key_exists('times', $_POST)) {
            if (preg_match_all('/^[1-9][0-9]*$/', $_POST['times']) == 0) {
                helpers::show_message('错误！非法的周目数！', 'error');
                return false;
            }
            $change['times'] = sanitize_text_field($_POST['times']);
        }
        $saved_bangumi[$id] = $change;
        uasort($saved_bangumi, 'self::sort_cmp');
        $flag = update_option("sinonbangumilist_savedbangumi", $saved_bangumi);
        if ($flag) {
            helpers::show_message('进度更新成功！', 'success');
        } else {
            helpers::show_message('进度更新失败！', 'error');
        }
        update_option('sinonbangumilist_index_status', false);
        return $flag;
    }

    //更新番剧信息
    public static function edit_process()
    {
        if (preg_match_all('/^[0-9]*$/', $_POST['count']) == 0) {
            helpers::show_message('错误！非法的总集数！', 'error');
            return false;
        }
        if (preg_match_all('/^[0-9][0-9][0-9][0-9]年[0-1]?[0-9]月[0-3]?[0-9]日$/', $_POST['date']) == 0) {
            helpers::show_message('错误！非法的首播日期！', 'error');
            return false;
        }
        $saved_bangumi = get_option("sinonbangumilist_savedbangumi");
        $id = (int) $_POST['bangumi_id'];
        $add = $saved_bangumi[$id];
        $add['img'] = esc_url_raw($_POST['img']);
        $add['url'] = esc_url_raw($_POST['url']);
        $add['name_cn'] = sanitize_text_field($_POST['name_cn']);
        $add['name'] = sanitize_text_field($_POST['name']);
        $add['date'] = sanitize_text_field($_POST['date']);
        $add['count'] = sanitize_text_field($_POST['count']);
        $add['title'] = sanitize_text_field($_POST['title']);
        $saved_bangumi[$id] = $add;
        $flag = update_option("sinonbangumilist_savedbangumi", $saved_bangumi);
        if ($flag) {
            helpers::show_message('番剧修改成功！', 'success');
        } else {
            helpers::show_message('番剧修改失败！', 'error');
        }
        update_option('sinonbangumilist_index_status', false);
        return $flag;
    }

    //添加新的番剧
    public static function add_bangumi_item()
    {
        if (preg_match_all('/^[1-9][0-9]*$/', $_POST['bangumi_id']) == 0) {
            helpers::show_message('错误！非法的番剧id！', 'error');
            return false;
        }
        if (preg_match_all('/^[0-9]*$/', $_POST['count']) == 0) {
            helpers::show_message('错误！非法的总集数！', 'error');
            return false;
        }
        if (preg_match_all('/^[0-9][0-9][0-9][0-9]年[0-1]?[0-9]月[0-3]?[0-9]日$/', $_POST['date']) == 0) {
            helpers::show_message('错误！非法的首播日期！', 'error');
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
        $add['times'] = 1;
        $saved_bangumi[$id] = $add;
        uasort($saved_bangumi, 'self::sort_cmp');
        $flag = update_option("sinonbangumilist_savedbangumi", $saved_bangumi);
        if ($flag) {
            helpers::show_message('番剧添加成功！', 'success');
        } else {
            helpers::show_message('番剧添加失败！', 'error');
        }
        update_option('sinonbangumilist_index_status', false);
        return $flag;
    }

    //删除所有番剧(删除数据库)
    public static function del_all_bangunmi()
    {
        $flag1 = delete_option("sinonbangumilist_savedbangumi");
        $flag2 = delete_option("sinonbangumilist_index_status");
        $flag3 = delete_option("sinonbangumilist_index");
        if ($flag1 && $flag2 && $flag3 == true) {
            helpers::show_message('番剧删除成功！', 'success');
        } else {
            helpers::show_message('番剧删除失败！', 'error');
        }
        return $flag;
    }

    //删除指定番剧
    public static function del_certain_bangunmi()
    {
        $saved_bangumi = get_option("sinonbangumilist_savedbangumi");
        $id = (int) $_POST['bangumi_id'];
        unset($saved_bangumi[$id]);
        uasort($saved_bangumi, 'self::sort_cmp');
        $flag = update_option("sinonbangumilist_savedbangumi", $saved_bangumi);
        if ($flag == true) {
            helpers::show_message('番剧删除成功！', 'success');
        } else {
            helpers::show_message('番剧删除失败！', 'error');
        }
        update_option('sinonbangumilist_index_status', false);
        return $flag;
    }

    //通过关键词搜索番剧
    //@return: 搜索结果
    public static function search_bangumi()
    {
        $keyword = sanitize_text_field($_POST['keyword']);
        $URL = "http://api.bgm.tv/search/subject/:" . urlencode($keyword) . "?type=2&responseGroup=Large&max_results=10";
        $request = wp_remote_get($URL);
        $response = wp_remote_retrieve_body($request);;
        $bg_json = json_decode($response, true);
        for ($i = 0; $i < 10; $i++) {
            if ($bg_json['list'][$i]['id'] == NULL) {
                break;
            }
            $result[$i]['id'] = $bg_json['list'][$i]['id'];
            $result[$i]['url'] = $bg_json['list'][$i]['url'];
            $result[$i]['name'] = $bg_json['list'][$i]['name'];
            $result[$i]['name_cn'] = $bg_json['list'][$i]['name_cn'];
            $result[$i]['img'] = $bg_json['list'][$i]['images']['large'];
        }
        return $result;
    }

    //获取保存的单个番剧信息
    //@id: 需要获取的番剧id
    //@return: 该番剧信息，null为不存在
    public static function get_bangumi_info(int $id)
    {
        $saved_bangumi = get_option("sinonbangumilist_savedbangumi");
        if (key_exists($id, $saved_bangumi))
            return $saved_bangumi[$id];
        else
            return null;
    }

    //内部方法，通过id获取番剧信息
    //@return: 番剧信息
    public static function get_bangumi_item(int $id)
    {
        $URL = "http://api.bgm.tv/subject/" . (string) $id . "?responseGroup=Small";
        $request = wp_remote_get($URL);
        $response = wp_remote_retrieve_body($request);;
        $bg_json = json_decode($response, true);
        $date = explode('-', $bg_json['air_date']);
        $date[1] = ltrim($date[1], '0');
        $date[2] = ltrim($date[2], '0');
        if ($date[1] == '') $date[1] = '0';
        if ($date[2] == '') $date[2] = '0';
        if ($bg_json['eps_count'] == "") $bg_json['eps_count'] = '0';
        //返回需要的数据
        $bangumi_item['url'] = $bg_json['url'];
        $bangumi_item['name_cn'] = $bg_json['name_cn'];
        $bangumi_item['name'] = $bg_json['name'];
        $bangumi_item['img'] = $bg_json['images']['large'];
        $bangumi_item['date'] = $date[0] . '年' . $date[1] . '月' . $date[2] . '日';
        $bangumi_item['count'] = $bg_json['eps_count'];
        $bangumi_item['title'] = $bg_json['summary'];
        $bangumi_item['id'] = $bg_json['id'];
        if ($bangumi_item['name_cn'] == "") {
            $bangumi_item['name_cn'] = $bg_json['name'];
            $bangumi_item['name'] = "";
        }
        return $bangumi_item;
    }

    //番剧排序比较函数
    private static function sort_cmp($a, $b)
    {
        if ($a['status'] == 1 && $b['status'] == 1) {
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
}
